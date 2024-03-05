<div class="container">
    <h3>Добавить недвижимость</h3>
    <form id="add_realty">
        <input type="hidden" name="action" value="add_realty">
        <div class="form-group">
            <label for="name">Название объекта</label>
            <input type="text" class="form-control" name="post_title" id="name" placeholder="Дом 1">
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-6">
                    <label for="realty_type">Тип недвижимости</label>
                    <select class="form-control" id="realty_type" name="realty_type">
                        <?php
                        $realty_types = get_terms( 'realty_type', ['hide_empty' => false,] );
                        foreach ($realty_types as $realty_type){
                            ?>
                            <option value="<?=$realty_type->term_id?>"><?=$realty_type->name?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-6">
                    <label for="cities">Город</label>
                    <select class="form-control" id="cities" name="cities">
                        <?php
                        $cities = get_posts( [ 'post_type' => 'cities' ] );
                        foreach ($cities as $city){
                            ?>
                            <option value="<?=$city->ID?>"><?=$city->post_title?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
         </div>
        <div class="form-group">
            <div class="form-row">
            <?php
            $meta_fiels = ['square'=>'Площадь', 'price'=>'Стоимость', 'address'=>'Адрес',
                'living_space'=>'Жилая площадь', 'floor'=>'Этаж'];
            foreach ($meta_fiels as $slug => $name){
                ?>
                <div class="form-group col-4">
                    <label for="<?=$slug?>"><?=$name?></label>
                    <input type="text" name="<?=$slug?>" class="form-control" id="<?=$slug?>">
                </div>
                <?php
            }
            ?>
                <div class="form-group col-4">
                    <label for="image">Фото</label>
                    <input type="file" name="image" class="form-control-file" id="image">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </div>
    </form>
</div>