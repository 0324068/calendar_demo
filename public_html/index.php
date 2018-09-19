<?php include('header.php')?>
<?php include('data.php')?>   
<?php include('temp.php')?> 
	<div id=panel data-year="<?=date('Y')?>" data-month="<?=date('m')?>">
        <div id=header >
            <?= date('Y') ?>/<?=date('m')?>
        </div>
        <div id="days" class="clearfix">
            <div class="day">SUN</div>
            <div class="day">MON</div>
            <div class="day">TUE</div>
            <div class="day">WED</div>
            <div class="day">THU</div>
            <div class="day">FRI</div>
            <div class="day">SAT</div>
        </div>
        
        <div id="dates" class="clearfix">
          <?php foreach ($dates as $key => $date): ?>    
            <div class="date-block <?=(is_null($date))?'empty'  : '' ?>" data-day="<?= $date ?>">
                <div class="date"><?= $date ?></div>
                <div class="events" data-from="">
                    
                </div>
            </div>
          <?php endforeach; ?>
        </div>
        
    </div>

    <div id="info-panel">
        <div class="close">X</div>
      <form >
        <input type="hidden" name="id" >
        <div class="title"><br>
            <label>Event</label>
            <input type="text" name="title">    
        </div>
        <div class="error-msg">
            <div class="alert alert-danger">error</div> 
        </div>
        <div class="time-picker">
            <div class=selected-date>
                <span class="mon"></span>/ <span class="date"></span>
                <input type="hidden" name="year">
                <input type="hidden" name="mon">
                <input type="hidden" name="date"> 
            </div>
            <div class="from">
                <label for="from">From</label><br>
                <input type="time" name="start_time" id="from">
            </div>
             <div class="to">
                <label for="to">To</label><br>
                <input type="time" name="end_time" id="to">
             </div>
        </div>
        <div class="content">
            <label >事件:</label><br>
            <textarea name="content" id="content" ></textarea>
        </div>
      </form>  
        <div class="buttons clearfix">
            <button class="create">create</button>
            <button class="update">update</button>
            <button class="cancel">cancel</button>
            <button class="delete">delete</button>
        </div>
    </div>
<?php include('footer.php')?>