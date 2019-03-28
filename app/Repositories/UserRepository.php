<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/19
 * Time: 10:08 PM
 */

namespace App\Repositories;


use App\Notifications\NewUserFollowNotification;
use App\User;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * @param $follower
     * @param $followed
     * @return mixed
     */
    public function follow($follower, $followed)
    {
        $user   = User::find($follower);
        $result = $user->followers()->toggle($followed);
        $this->updateFollowersCount(User::find($followed), !empty($result['attached']));

        return $result;
    }

    /**
     * @param User $user
     * @param $state
     * @return int
     */
    private function updateFollowersCount(User $user, $state)
    {
        if ( $state ) {
            $user->notify(new NewUserFollowNotification());
            return $user->increment('followers_count');
        } else {
            return $user->decrement('followers_count');
        }
    }

    /**
     * @param $follower
     * @param $followed
     * @return bool
     */
    public function isFollow($follower, $followed)
    {
        return !!User::find($follower)->followers->where('id',$followed)->count();
    }
}