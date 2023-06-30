   /** DATE PICKER **/

/* Get current year and fill the input 'year'*/
$(function () {
    thisyear = new Date().getFullYear();
    $('.year-selected').val(thisyear);
   });
   
   $(function () {
    moment.locale('id');
    
    var month1 = moment();
    var month2 = moment().subtract(1, 'y');
    var month3 = moment().subtract(2, 'y');
     for (let i = 0; i < 12; i ++)
       {
         let date = new Date(0, i, 1);
         let month = date.toLocaleString('default', { month: 'long' });
         $('.months').append('<a class="month" data-month='+('0' + (i+1)).slice(-2)+'>'+month.substring(0, 3)+'</a>');
        }
        $('.shortYears').append('<a class="yearShort" data-year="'+ month3.format('YYYY') +'">'+ month3.format('YYYY')  +'</a> ')
        $('.shortYears').append('<a class="yearShort" data-year='+ month2.format('YYYY') +'>'+ month2.format('YYYY')  +'</a> ')
        $('.shortYears').append('<a class="yearShort" data-year='+ month1.format('YYYY') +'>'+ month1.format('YYYY')  +'</a> ')
   })
   
   function switchSelectedYear(thisObj){
     
     let oldContent = thisObj.parent().parent().find('.date_pick-container .fake_input p').text();
                     let newContent = oldContent.slice(0,3) + thisObj.parent().parent().find('.date_selector .year-selected').val(); 
                     thisObj.parent().parent().find('.date_pick-container .fake_input p').text(newContent);
   }
   
           $('.fake_input').click(function() {
               let open = null;
   
               if($(this).parent().hasClass("date_pick")) {
                   open = ($(".date_selector.active").length > 0 &&  $(this).parent().find('.date_selector').length > 0 && ($(".date_selector.active")[0] === $(this).parent().find('.date_selector')[0]));
               } else if($(this).parent().hasClass("select_box")) {
                   open = ($(".box_selector.active").length > 0 &&  $(this).parent().find('.box_selector').length > 0 && ($(".box_selector.active")[0] === $(this).parent().find('.box_selector')[0]));
               }


   
               $(".date_selector.active").removeClass("active");
               $(".box_selector.active").removeClass("active");
               $(".select_box.active").removeClass("active");
               $(".date_pick.active").removeClass("active");
   
               if(!open) {
                   $(this).parent().find('.date_selector').toggleClass('active');
                   $(this).parent().find('.box_selector').toggleClass('active');
                   $(this).parent().toggleClass('active');
               }
           });
       
     $(document).on("click", function(e) {
           if ($(e.target).is(".date_selector.active a, .fake_input, .fake_input p, .date_selector.active .picker-select-arrow, .years, .years .year-selected, .date_selector.active div.months, .picker-arrow") === false) {
               $(".date_selector.active").removeClass("active");
               $(".box_selector.active").removeClass("active");
               $(".select_box.active").removeClass("active");
               $(".date_pick.active").removeClass("active");
           }
       });
   
       window.picker_date = function() {
           $('.picker-left').unbind("click");
           $('.picker-right').unbind("click");
   
           $('.picker-left').click(function(e){
             e.preventDefault;
               let value = (parseInt(($(this).parent().parent().find(".year-selected").val())) - 1);
               $(this).parent().find('.year-selected').val(value);
             
                     switchSelectedYear( $(this) );
           });
   
           $('.picker-right').click(function(e){
             e.preventDefault;
               let value = (parseInt(($(this).parent().parent().find(".year-selected").val())) + 1);
               $(this).parent().find('.year-selected').val(value);
             
                     switchSelectedYear( $(this) );
             
           });
       }
       window.picker_date();
   
   
   
   $('.date_pick-container .picker_date').keypress(function(event){
                   var keycode = (event.keyCode ? event.keyCode : event.which);
                   if(keycode == '13'){
                       event.preventDefault();  
                     
                       $(this).parent().click();
                   }
   })
   
   $('.date_selector .year-selected').keypress(function(event){
                   let keycode = (event.keyCode ? event.keyCode : event.which);
                   if(keycode == '13'){
                       event.preventDefault();
                     
                     $(this).parent().parent().parent().click();
        }
   });
   
   $('.date_selector .year-selected').on('input',function(e){
     var selector = e.target;
       switchSelectedYear(e);
   });