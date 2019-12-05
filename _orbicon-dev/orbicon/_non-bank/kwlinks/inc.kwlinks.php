<?php
/*

$replaceString = '';

string Parse(string content)
{
    const string regTagName = @"<.[^>]*>";
    
    Regex reg = new Regex(@"(" + regTagName + ")|(geekzilla)",
                     RegexOptions.IgnoreCase | RegexOptions.Multiline);

    // this is what I'd like to replace the match with
    replaceString = "<b>$1</b>";

    // do the replace
    content = reg.Replace(content, new MatchEvaluator(MatchEval));

    return content;
}

string MatchEval(Match match)
{
    if (match.Groups[1].Success) {
        // the tag
        return match.ToString();
    }
    if (match.Groups[2].Success) {
        // the text we're interested in
        return Regex.Replace(match.ToString(), "(.+)", replaceString);
    }
    // everything else
    return match.ToString();
}
*/
?>