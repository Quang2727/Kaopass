<?php
if (empty($questionData))
    return;

$link = Router::url(array(
            'controller' => 'questions',
            'action' => 'detail',
            'id' => $questionData['id'],
        ));
$title = $this->String->getShortText(h($questionData['title']), QUESTION_SUMMARY_TITLE_LENGTH);
$body = $this->String->getShortText(Markdown($questionData['body']), QUESTION_SUMMARY_BODY_LENGTH);
?>

<li data-href="/" class="clearfix">
    <p><?php echo $questionData['count']; ?><span>Theo dõi (clip)</span></p>
    <p class="searchTitle"><a href="<?php echo addslashes($link); ?>"><?php echo addslashes($title); ?></a></p>
</li>
