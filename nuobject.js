
function nuPropertiesObject(i){

	this.o   = Array();
	this.top = 40;
	var t = Array();
	var all = 'sob_all_type|sob_all_title|sob_all_name|sob_all_tab_title|sob_all_tab_number|sob_all_column_number|sob_all_order_number|sob_all_top|sob_all_left|';
	
	t['browse']    = all + 'sob_all_width|sob_all_height|sob_browse_zzzsys_form_id|sob_browse_filter';
	t['button']    = all + 'sob_all_width|sob_all_height|sob_button_zzzsys_form_id|sob_button_skip_browse_record_id|sob_button_browse_filter';
	t['display']   = all + 'sob_all_align|sob_display_sql';
	t['dropdown']  = all + 'sob_all_align|sob_dropdown_sql';
	t['checkbox']  = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql';
	t['html']      = all + 'sob_html_code';
	t['iframe']    = all + 'sob_all_width|sob_all_height|sob_all_read_only|sob_iframe_zzzsys_php_id|sob_iframe_zzzsys_report_id';
	t['listbox']   = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql|sob_listbox_sql';
	t['lookup']    = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql|sob_lookup_id_field|sob_lookup_code_field|sob_lookup_description_field|sob_lookup_code_width|sob_lookup_description_width|sob_lookup_autocomplete|sob_lookup_zzzsys_form_id|sob_lookup_javascript|sob_lookup_php';
	t['subform']   = all + 'sob_all_width|sob_all_height|sob_all_read_only|sob_subform_table|sob_subform_primary_key|sob_subform_foreign_key|sob_subform_row_height|sob_subform_addable|sob_subform_deletable|sob_subform_type|sob_subform_sql';
	t['text']      = all + 'sob_all_width|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql|sob_text_format|sob_text_type';
	t['textarea']  = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql';
	t['words']     = all + 'sob_all_align';


	this.ta = function(j){                                        //-- property needing a textarea

		var e = document.createElement('input');                 //-- create a new button object
		e.setAttribute('type', 'button');
		this.lower(20);

	}
	
	this.yn = function(j){                                        //-- property needing yes or no

		var e = document.createElement('input');                 //-- create a new checkbox object
		e.setAttribute('type', 'checkbox');
		this.lower(20);
		
	}
	
	this.dd = function(j){                                        //-- property needing a choice from a list

		var e          = document.createElement('select');       //-- create a new dropdown object
		this.lower(20);

	}

	this.tx = function(j){                                        //-- property needing text entered

		var e = document.createElement('input');                  //-- create a new text object
		e.setAttribute('type', 'text');
		
	}

	this.label = function(j){                                     //-- property needing text entered
	
		var e               = document.createElement('div');     //-- create a new field container
		e.setAttribute('id',   'textlabel'+this.top);
		$('#nuDrag').append(e);
		$('#' + e.id).css({
			'position'         : 'absolute',
			'top'              : this.top,
			'width'            : 200,
			'left'             : 2,
			'text-align'       : 'right'
		})
		$('#' + e.id).html('<strong>'+nuTranslate(t)'</strong>');
	
	}

	this.sob_all_type = function(j){
		this.label(j);
	}
	
	this.sob_all_title = function(j){
		this.label(j);
	}
	
	this.sob_all_name = function(j){
		this.label(j);
	}
	
	this.sob_all_tab_title = function(j){
		this.label(j);
	}
	
	this.sob_all_tab_number = function(j){
		this.label(j);
	}
	
	this.sob_all_column_number = function(j){
		this.label(j);
	}
	
	this.sob_all_order_number = function(j){
		this.label(j);
	}
	
	this.sob_all_top = function(j){
		this.label(j);
	}
	
	this.sob_all_left = function(j){
		this.label(j);
	}
	
	this.sob_all_width = function(j){
		this.label(j);
	}
	
	this.sob_all_height = function(j){
		this.label(j);
	}
	
	this.sob_all_clone = function(j){
		this.label(j);
	}
	
	this.sob_all_align = function(j){
		this.label(j);
	}
	
	this.sob_all_no_blanks = function(j){
		this.label(j);
	}
	
	this.sob_all_no_duplicates = function(j){
		this.label(j);
	}
	
	this.sob_all_read_only = function(j){
		this.label(j);
	}
	
	this.sob_all_display_condition = function(j){
		this.label(j);
	}
	
	this.sob_all_default_value_sql = function(j){
		this.label(j);
	}
	
	this.sob_button_zzzsys_form_id = function(j){
		this.label(j);
	}
	
	this.sob_button_skip_browse_record_id = function(j){
		this.label(j);
	}
	
	this.sob_button_browse_filter = function(j){
		this.label(j);
	}
	
	this.sob_display_sql = function(j){
		this.label(j);
	}
	
	this.sob_dropdown_sql = function(j){
		this.label(j);
	}
	
	this.sob_listbox_sql = function(j){
		this.label(j);
	}
	
	this.sob_lookup_id_field = function(j){
		this.label(j);
	}
	
	this.sob_lookup_code_field = function(j){
		this.label(j);
	}
	
	this.sob_lookup_description_field = function(j){
		this.label(j);
	}
	
	this.sob_lookup_code_width = function(j){
		this.label(j);
	}
	
	this.sob_lookup_description_width = function(j){
		this.label(j);
	}
	
	this.sob_lookup_autocomplete = function(j){
		this.label(j);
	}
	
	this.sob_lookup_zzzsys_form_id = function(j){
		this.label(j);
	}
	
	this.sob_lookup_javascript = function(j){
		this.label(j);
	}
	
	this.sob_lookup_php = function(j){
		this.label(j);
	}
	
	this.sob_subform_table = function(j){
		this.label(j);
	}
	
	this.sob_subform_primary_key = function(j){
		this.label(j);
	}
	
	this.sob_subform_foreign_key = function(j){
		this.label(j);
	}
	
	this.sob_subform_row_height = function(j){
		this.label(j);
	}
	
	this.sob_subform_addable = function(j){
		this.label(j);
	}
	
	this.sob_subform_deletable = function(j){
		this.label(j);
	}
	
	this.sob_subform_type = function(j){
		this.label(j);
	}
	
	this.sob_subform_sql = function(j){
		this.label(j);
	}
	
	this.sob_text_format = function(j){
		this.label(j);
	}
	
	this.sob_text_type = function(j){
		this.label(j);
	}
	
	this.sob_html_code = function(j){
		this.label(j);
	}
	
	this.sob_browse_zzzsys_form_id = function(j){
		this.label(j);
	}
	
	this.sob_browse_filter = function(j){
		this.label(j);
	}
	
	this.sob_iframe_zzzsys_php_id = function(j){
		this.label(j);
	}
	
	this.sob_iframe_zzzsys_report_id = function(j){
		this.label(j);
	}
	
}



/*

var bob = new nuPropertiesObject();
label.b('hello');


*/