$(document).ready(function(){

    var source   = $('#event-template').html();
    var template = Handlebars.compile(source);
    
    $.each(events,function (index,event) {
        var eventUI=template(event);
        var date=event['date'];
        
        $('#panel').find('.date-block[data-day="'+date+'"]').find('.events').append(eventUI);
    });

    var panel= {
        el:'#info-panel',
        selectedDB:'null',
        deleteDB:'null',
        init:function(isnew,e){
            
            panel.clear();
            panel.updated(e);
            if(isnew){
               $(panel.el).addClass('new').removeClass('update');
               panel.selectedDB=$(e.currentTarget);
           }
            else{
               $(panel.el).addClass('update').removeClass('new');
               panel.selectedDB=$(e.currentTarget).closest('.date-block');
               panel.deleteDB=$(e.currentTarget);
           }
              
        },
        clear:function () {
            $(panel.el).find('input').val('');
            $(panel.el).find('textarea').val('');
        },
        open:function(isnew,e){
            panel.hideerror();
            panel.init(isnew,e);
            var y,x;
            if(e.pageY<=267){
                y = e.pageY;
            }
            else
                y=230;
            if(e.pageX<=560){ 
                x=e.pageX+50;
            }                   
            else
                x=e.pageX-330;
                
            $(panel.el).addClass('open').css({           
                top:y+'px',
                left:x+'px'
            }).find('.title [contenteditable]').focus();
            
            
        } ,
        close:function(e){
            $('#info-panel').removeClass('open');
        },
        updated:function(e){
            var date ;
            if($(e.currentTarget).is('.date-block'))
                date= $(e.currentTarget).data('day');
            else
                date= $(e.currentTarget).closest('.date-block').data('day');
            var mon=$('#panel').data('month'); 
            var year=$('#panel').data('year');
           $(panel.el).find('.date').text(date);
           $(panel.el).find('.mon').text(year);
           $(panel.el).find('[name="mon"]').val(mon);
           $(panel.el).find('[name="date"]').val(date);
           $(panel.el).find('[name="year"]').val(year);
           
        },
        showerror:function(xhr){
            $(panel.el).find('.error-msg').addClass(' open').find('.alert').text(xhr.responseText);
        },
        hideerror:function(e) {
            $(panel.el).find('.error-msg').removeClass(' open');
        },
    };

    $('.date-block')
      .dblclick(function (e) {
            panel.open(true,e);            
        })
      .on('dblclick','.event',function (e) { 
            e.stopPropagation();
            panel.open(false,e);
            var id=$(e.currentTarget).data('id');         
            console.log(id);
            $.post('events/read.php',{id:id},function (data,textStatus,xhr) {
                $(panel.el).find('[name="id"]').val(data.id);
                $(panel.el).find('[name="title"]').val(data.title);
                $(panel.el).find('[name="start_time"]').val(data.start_time);
                $(panel.el).find('[name="end_time"]').val(data.end_time);
                $(panel.el).find('[name="content"]').val(data.content);
            });
            
        }) 
 
	
    $(panel.el)
      .on('click','button',function (e) {
         if($(this).is('.create')||$(this).is('.update')){
            
            if($(this).is('.create')){
                var action='events/create.php';
            }
            if($(this).is('.update')){
                var action='events/update.php';
                panel.deleteDB.remove();
            }
            var data = $(panel.el).find('form').serialize();
            
            $.post(action,data,function(data,textStatus,xhr){                          
 
                eventUI= template(data)
                
                panel.selectedDB.find('.event').each(function(index,event){
                    var fromtime = $(event).data('from').split(':');
                    var newfromtime=data.start_time.split(':');
                    if(newfromtime[0]<fromtime[0]||newfromtime[0]==fromtime[0]&&newfromtime[1]<fromtime[1]){
                        $(event).before(eventUI);
                        return false;                       
                    }                   
                    
                });
                if(panel.selectedDB.find('.event[data-id="'+data.id+'"]').length==0){
                    panel.selectedDB.find('.events').append(eventUI);
                }
                    
                panel.close(); 
              
            }).fail(function (xhr,textStatus,errorThrown) {
                panel.showerror(xhr);
            });
             
         }
         
         if($(this).is('.cancel')){
             panel.close(e);
         }
         if($(this).is('.delete')){
            var id = panel.deleteDB.data('id');    
            $.post('events/delete.php',{id:id}).done(function(){
                
                panel.close();
            });  
            
            
            panel.deleteDB.animate({left:"38px",height:"0px",width:"30px"},'2000',function(){
                panel.deleteDB.remove();
                }
            );
                
            
         }
      })
      .on('click','.close',function(e){
          $(' button.cancel').click();
      })
      
});