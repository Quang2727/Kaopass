<?php

App::uses('AppModel', 'Model');

/**
 * Vote Model
 *
 * @property User $User
 * @property Target $Target
 */
class UserVoteScore extends AppModel {

    public $virtualFields = array(
        'sum_total' => 'SUM(UserVoteScore.total)',
        'ranking' => 'row_number() OVER (ORDER BY SUM(UserVoteScore.total) DESC)'
    );

    function getDataTotalAll() {
        $dataToTal = $this->find("list", array(
            "conditions" => array(
                "UserVoteScore.type" => VOTE_ALL,
                "UserVoteScore.term" => TOTAL_ALL,
            ),
            "fields" => array("UserVoteScore.user_id", "UserVoteScore.total")
                ));
        return $dataToTal;
    }

    /**
     * デイリーのスコアをリセットする。
     * 
     * @param type $params
     */
    public function resetDailyScoreTotal() {
        return $this->updateAll(
            array('total' => 0),
            array(
                'type' => VOTE_SCORE,
                'term' => TOTAL_DAY
            )
        );
    }

    /**
     * デイリーのスコアをリセットする。
     * 
     * @param type $params
     */
    public function setScoreTotal($params) {
        return $this->updateAll(
            array(
                'total' => $params["total"],
                'modified' => "now()" 
            ),
            array(
                'user_id' => $params["user_id"],
                'type' => $params["type"],
                'term' => $params["term"],
            )
        );
    }
    
    public function setHighestRanking($params) {
        return $this->updateAll(
            array(
                'highest_ranking' => $params["highest_ranking"],
                'modified' => "now()" 
            ),
            array(
                'user_id' => $params["user_id"],
                'type' => $params["type"],
                'term' => $params["term"],
            )
        );
    }

    /**
     * 該当タイプのトータル数を指定の数だけ増加させる。
     * 
     * @param type $params
     */
    public function increaseTotal($params) {
        return $this->updateAll(
            array('total' => 'total + '.$params["num"]),
            array(
                'user_id' => $params["user_id"],
                'type' => $params["type"]
            )
        );
    }

    /**
     * 該当タイプのトータル数を指定の数だけ減少させる。
     * 
     * @param type $params
     */    
    public function decreaseTotal($params) {
        return $this->updateAll(
            array('total' => 'total - '.$params["num"]),
            array(
                'user_id' => $params["user_id"],
                'type' => $params["type"]
            )
        );
    }


    public function getUserRanking($id, $total) {

        $subQuery = $this->getDataSource()->buildStatement(array(
            'fields' => $this->getDataSource()->fields($this, NULL, array('UserVoteScore.user_id', 'UserVoteScore.sum_total', 'UserVoteScore.ranking', 'UserVoteScore.highest_ranking')),
            'group' => array('UserVoteScore.user_id', 'UserVoteScore.highest_ranking'),
            'conditions' => array('UserVoteScore.type' => VOTE_SCORE, 'UserVoteScore.term' => $total),
            'order' => array('UserVoteScore.sum_total' => 'DESC'),
            'table' => 'user_vote_scores',
            'alias' => 'UserVoteScore'
                ), $this);
        $return = $this->query("SELECT * FROM ($subQuery) AS \"Ranking\" WHERE \"Ranking\".\"UserVoteScore__user_id\" = $id");

        if (!empty($return)) {
            return $return[0];
        } else {
            return null;
        }
    }

    public function getTotalRankingList($params) {
    $sql = "select * from (select user_id,total,highest_ranking,rank() over(order by total desc) as ranking from user_vote_scores where type = :type and term = :term) rank";
    $rankingData = $this->query($sql,$params);
    $rankingList = array();
    foreach($rankingData as $value) {
        $rankingList[$value[0]["user_id"]] = $value[0]; 
    }
    return $rankingList;
    }
}
