	tinyMCE.init({
		mode : "exact",
		elements : "a",
		theme : "advanced",
		language : "hr",
		plugins : "safari,media,preview",

		theme_advanced_buttons1 : "undo,redo,|,cut,copy,paste,|,bold,italic,underline,strikethrough,|,formatselect,|,bullist,numlist,,sub,sup,|,link,unlink,image,media,|,preview",
		theme_advanced_buttons2 : '',
		theme_advanced_buttons3 : '',

		apply_source_formatting : false,
		element_format : "html",
		content_css : "./web/css/tinymce.css",

		valid_elements : "@[style|title|dir<ltr?rtl|lang],"
+ "a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
+ "name|href|target|title],strong/b,em/i,strike,u,"
+ "#p[align],-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
+ "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
+ "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
+ "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
+ "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
+ "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
+ "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,"
+ "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
+ "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
+ "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
+ "|height|src|*],map[name],area[shape|coords|href|alt|target],bdo,"
+ "col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
+ "valign|width],dfn,"
+ "kbd,label[for],legend,"
+ "q[cite],samp,small,"
+ "tt,var,big"

	});