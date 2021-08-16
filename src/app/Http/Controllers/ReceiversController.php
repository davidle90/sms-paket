<?php namespace Rocketlabs\Sms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use rl_campaigns;
use rl_products;

use Auth;
use DB;
use Rocketlabs\Campaigns\App\Jobs\AttachProducts;
use Rocketlabs\Sms\App\Models\Smsables;
use Validator;
use Lang;
use Session;
use config;

class ReceiversController extends Controller
{
    public function get(Request $request)
    {
        $form = [];
        $selected_form = [];

        parse_str($request->get('form', ''), $form);
        parse_str($request->get('selected_form', ''), $selected_form);

        $source_id      = $form['source'] ?? null;
        $search         = $form['search_input_receivers'] ?? null;
        $selected_ids   = $selected_form['selected_ids'] ?? [];

        $source         = Smsables::find($source_id);
        $model          = '\\'.$source->sourceable_type;
        $table          = app($model)->getTable();
      
        $receiversQuery = $model::query();

        $search_fields = explode(',' ,$source->search_fields);

        $select = [];
        $where  = [];

        $select[] = $table.'.*';

        foreach($search_fields as $key => $search_field) {
            $rel_table  = null;
            $column = null;

            if(str_contains($search_field, '.')) {
                $field_split  = explode('.', $search_field);

                $rel_table  = $field_split[0];
                $column     = trim($field_split[1]);
            } else {
                $column = trim($search_field);
            }

            if(str_contains($column, ':')) {
                $column = array_map('trim', explode(':', $column));
            }

            if(isset($rel_table)) {
                $temp_table = "temp_table_".$key;
                $receiversQuery->leftJoin($rel_table." AS ".$temp_table, $temp_table.'.id', '=', $table.".".str_singular($rel_table).'_id');
            }

            if(is_array($column)) {
                if(isset($rel_table)) {
                    $select[]   = DB::raw("CONCAT(".$temp_table.".".$column[0].",' ',".$temp_table.".".$column[1].") AS receiver_name");
                    $where[]    = DB::raw("CONCAT(".$temp_table.".".$column[0].",' ',".$temp_table.".".$column[1].")");
                } else {
                    $select[]   = DB::raw("CONCAT(".$column[0].",' ',".$column[1].") AS receiver_name");
                    $where[]    = DB::raw("CONCAT(".$column[0].",' ',".$column[1].")");
                }

            } else {
                if(isset($rel_table)) {
                    $select[]   = $temp_table.".".$column." AS receiver_name";
                    $where[]    = $temp_table.".".$column;
                } else {
                    $select[]   = $column." AS receiver_phone";
                    $where[]    = $column;
                }
            }

        }

        $receiversQuery->where(function($query) use($where, $search){
            foreach ($where as $condition) {
                $query->orWhere($condition, 'LIKE', '%'.$search.'%');
            }
        });

        $receiversQuery->select($select);
        $receiversQuery->orderBy('receiver_name', 'asc');

        $receivers = $receiversQuery->paginate(20);
        $receivers->withPath(route('rl_sms.admin.receivers.get'));

        return view('rl_sms::admin.pages.sms.modals.receivers.list', [
            'receivers'     => $receivers,
            'selected_ids'  => $selected_ids,
        ])->render();
    }

    public function move_all_receivers(Request $request)
    {
        $form = [];
        $selected_form = [];

        parse_str($request->get('form', ''), $form);
        parse_str($request->get('selected_form', ''), $selected_form);

        $source_id      = $form['source'] ?? null;
        $search         = $form['search_input_receivers'] ?? null;
        $selected_ids   = $selected_form['selected_ids'] ?? [];

        $source         = Smsables::find($source_id);
        $model          = '\\'.$source->sourceable_type;
        $table          = app($model)->getTable();

        $receiversQuery = $model::query();

        $search_fields = explode(',' ,$source->search_fields);

        $select = [];
        $where  = [];

        $select[] = $table.'.*';

        foreach($search_fields as $key => $search_field) {
            $rel_table  = null;
            $column = null;

            if(str_contains($search_field, '.')) {
                $field_split  = explode('.', $search_field);

                $rel_table  = $field_split[0];
                $column     = trim($field_split[1]);
            } else {
                $column = trim($search_field);
            }

            if(str_contains($column, ':')) {
                $column = array_map('trim', explode(':', $column));
            }

            if(isset($rel_table)) {
                $temp_table = "temp_table_".$key;
                $receiversQuery->leftJoin($rel_table." AS ".$temp_table, $temp_table.'.id', '=', $table.".".str_singular($rel_table).'_id');
            }

            if(is_array($column)) {
                if(isset($rel_table)) {
                    $select[]   = DB::raw("CONCAT(".$temp_table.".".$column[0].",' ',".$temp_table.".".$column[1].") AS receiver_name");
                    $where[]    = DB::raw("CONCAT(".$temp_table.".".$column[0].",' ',".$temp_table.".".$column[1].")");
                } else {
                    $select[]   = DB::raw("CONCAT(".$column[0].",' ',".$column[1].") AS receiver_name");
                    $where[]    = DB::raw("CONCAT(".$column[0].",' ',".$column[1].")");
                }

            } else {
                if(isset($rel_table)) {
                    $select[]   = $temp_table.".".$column." AS receiver_name";
                    $where[]    = $temp_table.".".$column;
                } else {
                    $select[]   = $column." AS receiver_phone";
                    $where[]    = $column;
                }
            }

        }

        $receiversQuery->where(function($query) use($where, $search){
            foreach ($where as $condition) {
                $query->orWhere($condition, 'LIKE', '%'.$search.'%');
            }
        });

        $receiversQuery->select($select);
        $receiversQuery->orderBy('receiver_name', 'asc');

        $receivers = $receiversQuery->get();

        return view('rl_sms::admin.pages.sms.modals.receivers.templates.rows', [
            'receivers'     => $receivers,
            'selected_ids'  => $selected_ids,
        ])->render();
    }

    public function update_receivers(Request $request)
    {
        $receivers          = $request->get('receivers', []);
        $receivers_manual   = $request->get('receivers_manual', []);
        $receivers_merged   = array_merge($receivers, $receivers_manual);

        return response()->json([
            'view' => view('rl_sms::admin.pages.sms.modals.templates.inputs', [
                'receivers' => $receivers_merged,
            ])->render(),
            'count' => count($receivers_merged)
        ]);
    }

}
