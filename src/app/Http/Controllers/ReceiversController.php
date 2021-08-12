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
        $search         = $form['search_input'] ?? null;
        $selected_ids   = $selected_form['receivers'] ?? [];

        $source = Smsables::find($source_id);
        $model = '\\'.$source->sourceable_type;
        
        $receiversQuery = $model::query();

        $search_fields = explode(',' ,$source->search_fields);

        foreach($search_fields as $search_field) {
            $table  = null;
            $column = null;

            if(str_contains($search_field, '.')) {
                $field_split  = explode('.', $search_field);

                $table  = $field_split[0];
                $column = $field_split[1];
            } else {
                $column = $search_field;
            }

            if(str_contains($column, ':')) {
                $column = explode(':', $column);
            }

            if(is_array($column)) {
                pre('Table: '.($table ?? 'none').',<br>'.'Column: '.$column[0].' AND '.$column[1], false);
            } else {
                pre('Table: '.($table ?? 'none').',<br>'.'Column: '.$column, false);
            }

        }

        pre('');

        //if(isset($search) && !empty($search)){
        //    $receiversQuery->leftJoin(config('rl_languages.tables.translations'), function($join) {
        //        $join->where(config('rl_languages.tables.translations').'.translatable_type', '=', 'Rocketlabs\Products\App\Models\Products');
        //        $join->on(config('rl_products.tables.products').'.id', '=', config('rl_languages.tables.translations').'.translatable_id');
        //        $join->where(config('rl_languages.tables.translations').'.key', '=', 'title');
        //        $join->on(config('rl_products.tables.products').'.primary_locale', '=', config('rl_languages.tables.translations').'.locale');
        //    });
        //
        //    $receiversQuery->select('*','products.id as id', config('rl_languages.tables.translations').'.'.'translation as title')->whereNotIn('products.id', $exclude_products);
        //
        //    $receiversQuery->where(config('rl_languages.tables.translations').'.translation', 'LIKE', '%' . $search . '%');
        //    $receiversQuery->orWhere('article_num', 'LIKE', $search);
        //    $receiversQuery->orWhere('ean_code', 'LIKE', $search);
        //    $receiversQuery->orWhere('products.id', 'LIKE', $search);
        //
        //    $receiversQuery->orWhereHas('combinations', function($query) use ($search){
        //        $query->where('article_num', 'LIKE', $search);
        //    });
        //
        //} else {
        //    $receiversQuery->whereNotIn('id', $exclude_products);
        //}

        $receivers = $receiversQuery->paginate(50);

        return view('rl_sms::admin.pages.sms.modals.receivers.list', [
            'receivers'     => $receivers,
            'selected_ids'  => $selected_ids,
            'name_key'      => 'users.firstname:lastname',
            'number_key'    => 'phone_sms'
        ])->render();
    }

    public function load(Request $request)
    {
        $campaign_id = $request->get('id', null);
        $campaign = $this->campaigns_model::find($campaign_id);

        // get campaign products
        $products = $campaign->products()->with('category')->paginate(50);

        $selected_combinations_ids = $campaign->products_combinations()
            ->whereHas('product', function($query) use($products){
                $query->whereIn('id', $products->pluck('id')->toArray());
            })
            ->pluck('combination_id')
            ->toArray();
        
        $products->withPath(route('rl_campaigns.admin.campaigns.products', ['campaign_id' => $campaign->id]));

        $table_classes = 'table table-striped table-white table-outline table-hover mb-0 border-secondary';

        return view('rl_campaigns::pages.admin.campaigns.includes.tables.products', [
            'campaign'      => $campaign,
            'products'      => $products,
            'selected_combinations_ids' => $selected_combinations_ids,
            'table_classes' => $table_classes
        ])->render();
    }

}
