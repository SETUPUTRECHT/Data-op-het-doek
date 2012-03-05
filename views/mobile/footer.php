<?php

$page['footer'] = '
<div id="footer">
<div id="bar">
<p>'.$page['footer'].'</p>
</div>
<div id="sections">

<div class="section">
{CMS elementname="footer1" handler="footerhead"}{/CMS}
<ul>
{CMS elementname="footer1_link1" handler="footerlinks"}{/CMS}
</ul>
</div>

<div class="section">
{CMS elementname="footer2" handler="footerhead"}{/CMS}
</div>

<div class="section">
{CMS elementname="footer3" handler="footerhead"}{/CMS}
</div>

<div class="clear"></div>
</div>

<div class="clear"></div>
</div>
';

?>