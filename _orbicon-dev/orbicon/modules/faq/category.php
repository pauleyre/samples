<?php

$orbicon_x->set_page_title(faq_get_category_title($_GET['cid']));

return '
<ul class="ic_list">
	'.faq_all($_GET['cid']).'
</ul>

<dl id="ic_categories">
	<dt><strong>Kategorije</strong></dt>
	'.faq_get_all_categories().'
</dl>';

?>