<?php

$question = faq_get_q(intval($_GET['qid']));
$orbicon_x->set_page_title(faq_get_category_title($question['category']));

return
'<h3 id="ccp">'.$question['title'].'</h3>
<div id="answer">'.
$question['answer'] . '
</div>
<dl class="ic_list topIndent">
	<dt><strong>'.faq_get_category_title($question['category']).'</strong></dt>
	'.faq_all($question['category'], true).'
</dl>

<dl id="ic_categories">
	<dt><strong>Kategorije</strong></dt>
	'.faq_get_all_categories().'
</dl>';

?>