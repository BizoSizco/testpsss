<div class="bs-video-slider owl-carousel" data-settings="{$slider_settings|@json_encode}">
    {foreach from=$videos item=video}
        <div class="item">
            <div class="video-thumbnail">
                {if $video.thumbnail}
                    <img src="{$image_path}{$video.thumbnail}" alt="{$video.title|escape:'html':'UTF-8'}" class="img-responsive">
                {else}
                    <div class="no-thumbnail">
                        {l s='No thumbnail available' mod='bs_videoslider'}
                    </div>
                {/if}
                <button class="play-button" data-video="{$video.video_content|escape:'html':'UTF-8'}">
                    <i class="icon-play"></i>
                </button>
            </div>
            {if $video.title}
                <h4 class="video-title">{$video.title|escape:'html':'UTF-8'}</h4>
            {/if}
        </div>
    {/foreach}
</div>