<div class="col-4 p-0">
    <div class="row d-flex border rounded m-1 flex-nowrap">
        <div class="h-100 border-primary justify-content-center"><a href="<?=get_post_permalink()?>"><?= get_the_post_thumbnail( get_the_ID(), 'thumbnail',['class' => "img-thumbnail"] ); ?></a></div>
        <div>
            <?php
            foreach (get_realty_meta(get_the_ID()) as $field_name => $field_val){
                ?>
                <div class="p-1"><b><?=$field_name?>:</b> <?=$field_val?></div>
                <?php
            }
            ?>
        </div>
    </div>
</div>