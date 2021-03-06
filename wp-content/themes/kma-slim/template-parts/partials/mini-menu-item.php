<div class="card menu-item <?= ($menuItem['photo'] != '' ? 'has-image' : ''); ?>">
    <?php if ($menuItem['photo'] != '') { ?>
        <div class="card-image" tabindex="0">
            <figure class="image is-16by9">
                <div class="card-content item-price-info columns is-multiline is-mobile is-gapless">
                    <div class="column has-text-left">
                        <p class="item-name dimbo"><?= $menuItem['name']; ?></p>
                    </div>
                    <div class="column is-narrow has-text-right">
                        <p class="item-price dimbo"><?= $menuItem['price']; ?></p>
                    </div>
                </div>
                <img src="<?= $menuItem['photo']; ?>" alt="<?= $menuItem['name']; ?>">
            </figure>
        </div>
    <?php } else { ?>
    <div class="card-content item-price-info columns is-multiline is-mobile is-gapless" tabindex="0">
        <div class="column">
            <p class="item-name dimbo"><?= $menuItem['name']; ?></p>
        </div>
        <div class="column is-narrow has-text-right">
            <p class="item-price dimbo"><?= $menuItem['price']; ?></p>
        </div>
    </div>
    <?php } ?>
    <?php if($menuItem['description'] != ''){ ?>
    <div class="card-content columns is-multiline is-mobile is-gapless" tabindex="0">
        <div class="column is-12">
            <p class="item-description"><?= $menuItem['description']; ?></p>
        </div>
    </div>
    <?php } ?>
</div>