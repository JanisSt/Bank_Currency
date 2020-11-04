<html>
<body>
<form action="/add" method="get">
    <button type="submit">Refresh the values</button>
</form>
<?php foreach ($currencies as $currency): ?>
    <li>
        <?php echo $currency->getCountry() . ' : ' . $currency->getRate(); ?>
    <br>
    </li>
<?php endforeach; ?>

</body>
</html>