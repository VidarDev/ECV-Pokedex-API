<?php
    $generations = $dao->getAllGenerations();
    $types = $dao->getAllTypes();
?>

<nav class="nav" id="nav">
    <div class="nav__button" id="nav-menu">
        <img src="./img/pokeball.svg" role='img' alt='Bouton du menu' title='Bouton du menu' aria-label='Bouton du menu' loading='lazy' width='200' height='200'/>
    </div>
    <div class="nav__content">
        <form action="index.php" method="post" class="filter" name="filter">
            <label for="generation" class="screen-reader-only"></label>
            <select id="generation" class="select" name="generation">
                <option value="0" selected>Tous</option>
                <?php foreach($generations as $generation): ?>
                    <option value="<?= $generation['generation']?>">Gen <?= $generation['generation']?></option>
                <?php endforeach; ?>
            </select>
            <fieldset class="type">
                <label for="all-types" class="all">
                    <input type="radio" id="all-types" name="types" value="0" checked>
                    <img src='./img/icons/shuffle.svg' role='img' alt='Tous les types' title='Tous les types' aria-label='Tous les types' loading='lazy' width='16' height='16'/>
                </label>
                <?php foreach($types as $type): ?>
                    <label for="<?= $type['id_type'] ?>">
                        <input type="radio" id="<?= $type['id_type'] ?>" name="types" value="<?= $type['id_type'] ?>">
                        <img src='<?= $type['image'] ?>' role='img' alt='<?= $type['name_FR'] ?>' title='<?= $type['name_FR'] ?>' aria-label='<?= $type['name_FR'] ?>' loading='lazy' width='16' height='16'/>
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <div class="pagination">
                <button type="button" class="btn prev-page" id="prev-page" value="0">
                    <img src='./img/icons/chevron-back.svg' role='img' alt='Page précédente' title='Page précédente' aria-label='Page précédente' loading='lazy' width='16' height='16'/>
                </button>
                <button type="button" class="btn actual-page" id="actual-page" data-content="1" value="1"></button>
                <button type="button" class="btn next-page" id="next-page" value="2">
                    <img src='./img/icons/chevron-forward.svg' role='img' alt='Page suivante' title='Page suivante' aria-label='Page suivante' loading='lazy' width='16' height='16'/>
                </button>
            </div>
        </form>
        <div class="card-container">
            <!-- Function AJAX-->
        </div>
    </div>
</nav>