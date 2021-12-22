$(document).ready(function() { 
    
   /*$('input[name=date_of_birth]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2020',
        dateFormat: 'yy-mm-dd',
        gotoCurrent: true
    });
    
    $('input[name=date_of_birth_from]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2020',
        dateFormat: 'yy-mm-dd',
        gotoCurrent: true
    });
	
    $('input[name=date_of_birth_to]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2020',
        dateFormat: 'yy-mm-dd',
        gotoCurrent: true
    });
    
    $('input[name=date_of_marriage_anniversary]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2020',
        dateFormat: 'yy-mm-dd',
        gotoCurrent: true
    });
    
    $('input[name=spouse_birthday]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2020',
        dateFormat: 'yy-mm-dd',
        gotoCurrent: true
    });
    
    $('input[name=cd_date1]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2020',
        dateFormat: 'yy-mm-dd',
        gotoCurrent: true
    });
    
    $.ajax({
        type: 'POST',
        url: "getData.php?case=bp_title",
        dataType: 'json',
        success: function(jsonObject){
            var suggestions = [];
            $.each(jsonObject, function(i, val){
                suggestions.push(val.value);
            });
            $("input[name=title]" ).autocomplete({
                source: suggestions,
                minLength: 1,
                delay: 100,
                max: 10
            });
        },
        error: function(){
            alert("Error retrieving bp_title autocomplete");
        }
    });
    
    $.ajax({
        type: 'POST',
        url: "getData.php?case=bp_profession",
        dataType: 'json',
        success: function(jsonObject){
            var suggestions = [];
            $.each(jsonObject, function(i, val){
                suggestions.push(val.value);
            });
            $("input[name=bp_profession]" ).autocomplete({
                source: suggestions,
                minLength: 1,
                delay: 100,
                max: 10
            });
        },
        error: function(){
            alert("Error retrieving bp_profession autocomplete");
        }
    });

    $.ajax({
        type: 'POST',
        url: "getData.php?case=bp_reference",
        dataType: 'json',
        success: function(jsonObject){
            var suggestions = [];
            $.each(jsonObject, function(i, val){
                suggestions.push(val.value);
            });
            $("input[name=bp_reference]" ).autocomplete({
                source: suggestions,
                minLength: 1,
                delay: 100,
                max: 10
            });
        },
        error: function(){
            alert("Error retrieving bp_reference autocomplete");
        }
    });
    
    $.ajax({
        type: 'POST',
        url: "getData.php?case=bp_source",
        dataType: 'json',
        success: function(jsonObject){
            var suggestions = [];
            $.each(jsonObject, function(i, val){
                suggestions.push(val.value);
            });
            $("input[name=bp_source]" ).autocomplete({
                source: suggestions,
                minLength: 1,
                delay: 100,
                max: 10
            });
        },
        error: function(){
            alert("Error retrieving bp_source autocomplete");
        }
    });
    
    $.ajax({
        type: 'POST',
        url: "getData.php?case=current_accomodation",
        dataType: 'json',
        success: function(jsonObject){
            var suggestions = [];
            $.each(jsonObject, function(i, val){
                suggestions.push(val.value);
            });
            $("input[name=current_accomodation]" ).autocomplete({
                source: suggestions,
                minLength: 1,
                delay: 100,
                max: 10
            });
        },
        error: function(){
            alert("Error retrieving current_accomodation autocomplete");
        }
    });
    
    $.ajax({
        type: 'POST',
        url: "getData.php?case=bp_sales_rep",
        dataType: 'json',
        success: function(jsonObject){
            var suggestions = [];
            $.each(jsonObject, function(i, val){
                suggestions.push(val);
            });
            $("input[name=sales_rep_name]").autocomplete({
                source: suggestions,
                minLength: 1,
                delay: 100,
                max: 10,
                autoFill: true,
                focus: function(event, ui) {
                    $("input[name=sales_rep_id]").val(ui.item.value);
                    $(this).val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    $("input[name=sales_rep_id]").val(ui.item.value);
                    $(this).val(ui.item.label);
                    return false;
                }
            });
        },
        error: function(){
            alert("Error retrieving current_accomodation autocomplete");
        }
    });*/
});

    

