<div class="panel">
    <div class="panel-heading">
        <i class="icon-film"></i> {l s='Videos' mod='bs_videoslider'}
        <span class="badge">{count($videos_list)}</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="fixed-width-xs">ID</th>
                    <th>{l s='Title' mod='bs_videoslider'}</th>
                    <th class="fixed-width-xs">{l s='Position' mod='bs_videoslider'}</th>
                    <th class="fixed-width-xs text-right">{l s='Actions' mod='bs_videoslider'}</th>
                </tr>
            </thead>
            <tbody class="sortable">
                {foreach from=$videos_list item=video}
                <tr id="video_{$video.id}">
                    <td>{$video.id}</td>
                    <td>{$video.title}</td>
                    <td class="dragHandle center">
                        <div class="dragGroup">
                            <span class="positions">{$video.position}</span>
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a href="{$current|escape:'html':'UTF-8'}&updatebs_videoslider_video&id={$video.id}"
                                class="btn btn-default">
                                <i class="icon-pencil"></i>
                            </a>
                            <button class="btn btn-default delete-video" data-id="{$video.id}">
                                <i class="icon-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.sortable').sortable({
        axis: 'y',
        update: function() {
            var order = $(this).sortable('serialize');
            $.post('{$ajax_url}', order, function(response){
                if(response.success) {
                    showSuccessMessage('Positions updated');
                }
            });
        }
    });
});
</script>