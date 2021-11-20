<html lang="de">
<?php include "resources/views/head.php"; ?>
<body>
<div id="page">
    <?php include 'resources/views/left-bar.php'; ?>
    <div id="main">
        <div id="content" class="category">
            <div id="text">
                <h1 class="title">Korbball</h1>
                <p class="abstract">
                    Ballgefühl, Geschicklichkeit, Ausdauer, Kraft und und mit so Einigem mehr trainieren wir mit viel Ehrgeiz und Freude.
                    Das ganze Jahr über sind wir aktiv, im Sommer auf dem Rasen und im Winter in der Sporthalle.
                </p>
                <div class="posts">
                    <?php for($i = 0; $i < 3; $i++) { ?>
                    <div class="post">
                        <h2 class="title">Junge Menznauerinnen souverän für die SM qualifiziert</h2>
                        <p class="meta">
                            <span class="date">20.10.2021</span>
                            <span class="breadcrumbs">Jugend</span>
                        </p>
                        <p class="abstract">
                            In Küssnacht fand die Qualifikation für die Jugend-Schweizermeisterschaft im Korbball statt.
                            Das u14-Mädchen-Team des SVKT Menznau war dabei mit zwei Teams vertreten.
                        </p>
                        <a href="single.php" class="read-more">mehr lesen</a>
                    </div>
                    <div class="post">
                        <h2 class="title">Korbballerinnen erscheinen im neuen Look</h2>
                        <p class="meta">
                            <span class="date">20.10.2021</span>
                            <span class="breadcrumbs">Jugend</span>
                        </p>
                        <p class="abstract">
                            Sehnsüchtig wartend durften die Korbballerinnen des SVKT Menznau am 10. September 2020 ihr
                            neues Dress entgegennehmen und so auf eine neue Ära anstossen.
                        </p>
                        <a href="single.php" class="read-more">mehr lesen</a>
                    </div>
                    <div class="post">
                        <h2 class="title">Vorrunde der Innerschweizer Korbballmeisterschaft ist vorbei</h2>
                        <p class="meta">
                            <span class="date">20.10.2021</span>
                            <span class="breadcrumbs">ISM</span>
                        </p>
                        <p class="abstract">
                            Sehnsüchtig wartend durften die Korbballerinnen des SVKT Menznau am 10. September 2020 ihr
                            neues Dress entgegennehmen und so auf eine neue Ära anstossen.
                        </p>
                        <a href="single.php" class="read-more">mehr lesen</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="fill-area"></div>
        <?php include 'resources/views/footer.php'; ?>
    </div>
</body>
</html>