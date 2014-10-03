<?php
/**
 * Parse query to array
 *
 * @package app.Model
 * @author Mai Nhut Tan
 * @since 2013/09/26
 */
class SearchTerm{

    /**
     * Explaination
     *
     * @var string  $query      original query
     * @var string  $text       trimmed query without tags
     * @var array   $tags       tags from query
     * @var array   $scopes     all sigle words in $text
     * @var boolean $exact      exactly search
     */
    public $query = '';
    public $text = '';
    public $tags = array();
    public $scopes = array();
    public $exact = false;

    /** 検索結果の画面部分に表示するクエリ */
    public $display_query = '';
    /** 検索には用いない、画面から入力されたタグ形式の文字列 */
    public $display_tags = array();
    /** タグ検索結果 */
    public $tag_filter = array();

    /**
     * Constructor
     *
     * @param string $query
     * @return void
     * @author Mai Nhut Tan
     * @since 2013/09/26
     */
    public function __construct($query = ''){
        if(!empty($query)){
            $this->parse($query);
        }
    }

    /**
     * Parse query text to array
     *
     * @param string $query
     * @return void
     * @author Mai Nhut Tan
     * @since 2013/09/26
     */
    public function parse($query = ''){
        $filter = '/\040*\[\040*([^\]]+)\040*\]\040*/';
        $spliter = '/[(\s|\x{3000})\,]+/u';
        $enclosure = '/("|\')(.+?)\1/';

        preg_match_all($filter, $query, $matches);
        $original_tags = empty($matches) ? array() : $matches[1];
        $this->tags = $original_tags;

        $tmp = preg_replace($filter, ' ', trim($query));
        preg_match_all($enclosure, $tmp, $scopes);
        $scopes = $scopes[0];
        $this->scopes = array_merge($scopes, preg_split($spliter, preg_replace($enclosure, ' ', $tmp), -1, PREG_SPLIT_NO_EMPTY));

        $scopes = array();
        foreach ($this->scopes as $scope) {
            if (0 < preg_match($enclosure, $scope)) {
                $scopes[] = preg_replace($enclosure, '\2', $scope);
            } else {
                $scopes[] = $scope;
            }
        }

        /* 入力されたフリーワードにタグがあるかチェック */
        $in = array_merge(
            array_map('strtolower', $scopes),
            array_map('strtolower', $this->tags)
        );
        $Tag = ClassRegistry::init('Tag');
        $result = $Tag->find('list', array(
            'conditions' => array(
                'LOWER(Tag.name)' => array_unique($in),
                'Tag.delete_flag' => FLAG_NOT_DELETED,
            ),
        ));
        $matches = array();
        $this->tags = array();
        $this->tag_filter = array();
        foreach ($result as $key => $word) {
            $this->tag_filter[$key] = $key;
            $offsets = array_keys($in, strtolower($word));
            foreach ($offsets as $offset) {
                $matches[$in[$offset]] = true;
                unset($this->scopes[$offset]);
                unset($scopes[$offset]);
            }
            $this->tags[] = $word;
        }
        $this->tags = array_unique($this->tags);
        /* タグ形式だがタグにマッチしない場合、タグ検索には用いないがフォームの表示上ではタグ形式のままとする */
        $diff = array();
        foreach ($original_tags as $word) {
            if (!isset($matches[strtolower($word)])) {
                $diff[] = $word;
            }
        }
        $this->display_tags = $this->tags;
        if ($diff) {
            $this->display_tags = array_unique(array_merge($this->display_tags, $diff));
        }

        $this->text = implode(' ', array_unique($this->scopes));
        $this->scopes = array_unique(array_map('strtolower', $scopes));

        $this->query = '';
        $this->display_query = '';
        if ($this->display_tags) {
            $this->query .= '[' . implode('] [', $this->display_tags) . ']';
            $this->display_query .= implode(' ', $this->display_tags);
        }
        if ('' !== $this->text) {
            $q = '';
            if ('' !== $this->query) {
                $q .= ' ';
            }
            $q .= $this->text;

            $this->query .= $q;
            $this->display_query .= $q;
        }
    }
}
