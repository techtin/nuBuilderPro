
function nuOneObject(id){

	var o = JSON.parse(nuFORM.object_properties);
	
	for(var i = 0 ; i < o.object.length ; i++){
		if(o.object[i]['zzzsys_object_id'] == id){
			return o.object[i];
		}
	}
	
}

	
function nuPropertiesObject(id){

	this.o                  = nuOneObject(id);
	this.top                = 20;
	this.types              = Array();
	this.types_list         = 'browse|Browse||button|Button||display|Display||dropdown|Drop Down||checkbox|Check Box||html|HTML||iframe|iFrame||listbox|List Box||lookup|Lookup||subform|SubForm||text|Text||textarea|Textarea||words|Words|';
	var all                 = 'sob_all_type|sob_all_title|sob_all_name|sob_all_tab_title|sob_all_tab_number|sob_all_column_number|sob_all_order_number|sob_all_top|sob_all_left|';
	
	this.types['browse']    = all + 'sob_all_width|sob_all_height|sob_browse_zzzsys_form_id|sob_browse_filter';
	this.types['button']    = all + 'sob_all_width|sob_all_height|sob_button_zzzsys_form_id|sob_button_skip_browse_record_id|sob_button_browse_filter';
	this.types['display']   = all + 'sob_all_width|sob_all_align|sob_display_sql';
	this.types['dropdown']  = all + 'sob_all_width|sob_all_align|sob_dropdown_sql';
	this.types['checkbox']  = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql';
	this.types['html']      = all + 'sob_all_width|sob_all_height|sob_html_code';
	this.types['iframe']    = all + 'sob_all_width|sob_all_height|sob_all_read_only|sob_iframe_zzzsys_php_id|sob_iframe_zzzsys_report_id';
	this.types['listbox']   = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql|sob_listbox_sql';
	this.types['lookup']    = all + 'sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql|sob_lookup_id_field|sob_lookup_code_field|sob_lookup_description_field|sob_lookup_code_width|sob_lookup_description_width|sob_lookup_autocomplete|sob_lookup_zzzsys_form_id|sob_lookup_javascript|sob_lookup_php';
	this.types['subform']   = all + 'sob_all_width|sob_all_height|sob_all_read_only|sob_subform_table|sob_subform_primary_key|sob_subform_foreign_key|sob_subform_row_height|sob_subform_addable|sob_subform_deletable|sob_subform_type|sob_subform_sql';
	this.types['text']      = all + 'sob_all_width|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql|sob_text_format|sob_text_type';
	this.types['textarea']  = all + 'sob_all_width|sob_all_height|sob_all_clone|sob_all_align|sob_all_no_blanks|sob_all_no_duplicates|sob_all_read_only|sob_all_display_condition|sob_all_default_value_sql';
	this.types['words']     = all + 'sob_all_width|sob_all_align';

	nuObjectDraggableDialog(450);

	var e = document.createElement('div');                   //-- create a new field container
	e.setAttribute('id',   'nuDragProperties');
	$('#nuDrag').append(e);
	$('#' + e.id).css({
		'position'         : 'absolute',
		'top'              : 30,
		'width'            : 448,
		'left'             : 0,
		'height'           : 400,
		'overflow'         : 'auto',
		'overflow-x'       : 'hidden',	
		'text-align'       : 'left'
	})


	this.showProperties = function(){

		var p          = String(this.types[this.o.sob_all_type]).split('|');
		for(var i = 0 ; i < p.length ; i++){
			this[p[i]](p[i]);
		}

	}

	this.button = function(fld){                                    //-- property needing a textarea

		var e = document.createElement('input');                   //-- create a new button object
		e.setAttribute('id',   'property_'+fld);
		e.setAttribute('type', 'button');

	}
	
	this.checkbox = function(fld){                                  //-- property needing yes or no

		var e = document.createElement('input');                   //-- create a new checkbox object
		e.setAttribute('id',   'property_'+fld);
		e.setAttribute('type', 'checkbox');
		
	}
	
	this.dropdown = function(fld){                                 //-- property needing a choice from a list

		var e = document.createElement('select');                  //-- create a new dropdown object
		e.setAttribute('id',   'property_'+fld);
		$('#nuDragProperties').append(e);
		$('#' + e.id).css({
			'position'         : 'absolute',
			'top'              : this.top - 25,
			'width'            : 100,
			'left'             : 190,
			'text-align'       : 'left'
		})

	}

	this.text = function(fld){                                     //-- property needing text entered

		var e = document.createElement('input');                  //-- create a new text object
		e.setAttribute('id',   'property_'+fld);
		e.setAttribute('type', 'text');
		$('#nuDragProperties').append(e);
		$('#' + e.id).css({
			'position'         : 'absolute',
			'top'              : this.top - 25,
			'width'            : 200,
			'left'             : 190,
			'text-align'       : 'left'
		})
		$('#' + e.id).val(this.o[fld]);
		
		
	}

	this.label = function(title, field){                           //-- property needing text entered
	
		var e = document.createElement('div');                    //-- create a new field container
		e.setAttribute('id',   'title_property_'+field);
		$('#nuDragProperties').append(e);
		$('#' + e.id).css({
			'position'         : 'absolute',
			'top'              : this.top,
			'width'            : 180,
			'left'             : 2,
			'text-align'       : 'right'
		})

		$('#' + e.id).html(nuTranslate(title)+' :');

		this.top = Number(this.top) + 25;
		
	}

	this.sob_all_type = function(fld){
		
		this.label('Type', fld);
		
		var a = Array();
		a.push(new this.O('','',''));
		a.push(new this.O('browse','Browse',''));
		a.push(new this.O('button','Button',''));
		a.push(new this.O('display','Display',''));
		a.push(new this.O('dropdown','Drop Down',''));
		a.push(new this.O('checkbox','Check Box',''));
		a.push(new this.O('html','HTML',''));
		a.push(new this.O('iframe','iFrame',''));
		a.push(new this.O('listbox','List Box',''));
		a.push(new this.O('lookup','Lookup',''));
		a.push(new this.O('subform','Subform',''));
		a.push(new this.O('text','Text',''));
		a.push(new this.O('textarea','Textarea',''));
		a.push(new this.O('word','Word',''));

		this.dropdown(fld);
		this.nuFillDropdown(fld, a, this.o[fld]);
		
		
	}
	
	this.sob_all_title = function(fld){
		this.label('Title', fld);
		this.text(fld);
	}
	
	this.sob_all_name = function(fld){
		this.label('Field', fld);
		this.text(fld);
	}
	
	this.sob_all_tab_title = function(fld){
		this.label('Tab Title', fld);
		this.text(fld);
	}
	
	this.sob_all_tab_number = function(fld){
		this.label('Tab Number', fld);
		this.text(fld);
	}
	
	this.sob_all_column_number = function(fld){
		this.label('Column Number', fld);
		this.text(fld);
	}
	
	this.sob_all_order_number = function(fld){
		this.label('Order Number', fld);
		this.text(fld);
	}
	
	this.sob_all_top = function(fld){
		this.label('Top Pixels', fld);
		this.text(fld);
	}
	
	this.sob_all_left = function(fld){
		this.label('Left Pixels', fld);
		this.text(fld);
	}
	
	this.sob_all_width = function(fld){
		this.label('Width Pixels', fld);
		this.text(fld);
	}
	
	this.sob_all_height = function(fld){
		this.label('Height Pixels', fld);
		this.text(fld);
	}
	
	this.sob_all_clone = function(fld){
		this.label('Allow Cloning', fld);
	}
	
	this.sob_all_align = function(fld){
		this.label('Align', fld);
	}
	
	this.sob_all_no_blanks = function(fld){
		this.label('Stop Blanks', fld);
	}
	
	this.sob_all_no_duplicates = function(fld){
		this.label('Stop Duplicates', fld);
	}
	
	this.sob_all_read_only = function(fld){
		this.label('Read Only', fld);
	}
	
	this.sob_all_display_condition = function(fld){
		this.label('Display Condition', fld);
	}
	
	this.sob_all_default_value_sql = function(fld){
		this.label('Default Value', fld);
	}
	
	this.sob_button_zzzsys_form_id = function(fld){
		this.label('Form To Launch via Browse', fld);
	}
	
	this.sob_button_skip_browse_record_id = function(fld){
		this.label('Record ID', fld);
	}
	
	this.sob_button_browse_filter = function(fld){
		this.label('Default Filter for Browse Screen', fld);
		this.text(fld);
	}
	
	this.sob_display_sql = function(fld){
		this.label('SQL', fld);
	}
	
	this.sob_dropdown_sql = function(fld){
		this.label('SQL', fld);
	}
	
	this.sob_listbox_sql = function(fld){
		this.label('SQL', fld);
	}
	
	this.sob_lookup_id_field = function(fld){
		this.label('ID Field', fld);
		this.text(fld);
	}
	
	this.sob_lookup_code_field = function(fld){
		this.label('Code Field', fld);
		this.text(fld);
	}
	
	this.sob_lookup_description_field = function(fld){
		this.label('Description Field', fld);
		this.text(fld);
	}
	
	this.sob_lookup_code_width = function(fld){
		this.label('Code Width', fld);
		this.text(fld);
	}
	
	this.sob_lookup_description_width = function(fld){
		this.label('Description Width', fld);
		this.text(fld);
	}
	
	this.sob_lookup_autocomplete = function(fld){
		this.label('Autocomplete', fld);
	}
	
	this.sob_lookup_zzzsys_form_id = function(fld){
		this.label('Form to Lookup', fld);
	}
	
	this.sob_lookup_javascript = function(fld){
		this.label('Javascript', fld);
	}
	
	this.sob_lookup_php = function(fld){
		this.label('PHP Functions', fld);
	}
	
	this.sob_subform_table = function(fld){
		this.label('Table Name', fld);
		this.text(fld);
	}
	
	this.sob_subform_primary_key = function(fld){
		this.label('Primary Key', fld);
		this.text(fld);
	}
	
	this.sob_subform_foreign_key = function(fld){
		this.label('Foreign Key', fld);
		this.text(fld);
	}
	
	this.sob_subform_row_height = function(fld){
		this.label('Row Height', fld);
		this.text(fld);
	}
	
	this.sob_subform_addable = function(fld){
		this.label('Addable', fld);
	}
	
	this.sob_subform_deletable = function(fld){
		this.label('Deleteable', fld);
	}
	
	this.sob_subform_type = function(fld){
		this.label('Subform Type', fld);
	}
	
	this.sob_subform_sql = function(fld){
		this.label('SQL', fld);
	}
	
	this.sob_text_format = function(fld){
		this.label('Format', fld);
	}
	
	this.sob_text_type = function(fld){
		this.label('Text Type', fld);
	}
	
	this.sob_html_code = function(fld){
		this.label('HTML', fld);
	}
	
	this.sob_browse_zzzsys_form_id = function(fld){
		this.label('Browse', fld);
	}
	
	this.sob_browse_filter = function(fld){
		this.label('Default Filter for Browse', fld);
		this.text(fld);
	}
	
	this.sob_iframe_zzzsys_php_id = function(fld){
		this.label('PHP to Run', fld);
	}
	
	this.sob_iframe_zzzsys_report_id = function(fld){
		this.label('Report to Run', fld);
	}



	this.nuFillDropdown = function(fld, arr, val){
debugger;
		var e        = document.getElementById('property_'+fld);

		for(var i = 0 ; i < arr.length ; i++){

			var o   = document.createElement('option');
			o.value = arr[i].id;
			
			o.appendChild(document.createTextNode(arr[i].code));
			
			if(arr[i].id == val){
				o.setAttribute('selected', 'selected');
			}
			
			e.appendChild(o);
			
		}

	}

	this.O = function(i, c, d){
		
		this.id          = i;
		this.code        = c;
		this.description = d;
		
	}
	



	
}

