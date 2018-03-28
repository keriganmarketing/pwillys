<div class="card menu-item <?= ($menuItem['photo'] != '' ? 'has-image' : ''); ?>">
    <?php if ($menuItem['photo'] != '') { ?>
        <div class="card-image">
            <figure class="image is-16by9">
                <div class="card-content item-price-info columns is-multiline is-mobile is-gapless">

                    <div class="column">
                        <p class="item-name dimbo"><?= $menuItem['name']; ?></p>
                    </div>

                    <div class="column is-narrow has-text-right">
                        <p class="item-price dimbo"><?= $menuItem['price']; ?></p>
                    </div>
                </div>
                <img src="<?= $menuItem['photo']; ?>"
                     alt="<?= $menuItem['name']; ?>">
            </figure>
        </div>
    <?php } else { ?>
    <div class="card-content item-price-info columns is-multiline is-mobile is-gapless">
        <div class="column">
            <p class="item-name dimbo"><?= $menuItem['name']; ?></p>
        </div>
        <div class="column is-narrow has-text-right">
            <p class="item-price tandelle"><?= $menuItem['price']; ?></p>
        </div>
    </div>
    <?php } ?>
    <div class="card-content columns is-multiline is-mobile is-gapless">
        <div class="column is-12">
            <p class="item-description"><?= $menuItem['description']; ?></p>
        </div>
    </div>
</div>