{capture name=path}{l s='Video Sliders' mod='bs_videoslider'}{/capture}

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="icon-film"></i> {l s='Video Sliders' mod='bs_videoslider'}
    </div>
    <div class="table-responsive">
        {$table}
    </div>
</div>

<script>
$(document).ready(function(){
    $('.table').addClass('table-striped');
});
</script>