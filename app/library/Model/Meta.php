<?php
namespace App\Model;

class Meta extends Base
{
    protected $table = 'meta';

    protected $fillable = array(
        'name',
        'value',
        'user_id',
    );

    public static $validNames = array(
        'facebook_id',
        'source',
    );

    public function user()
    {
        return $this->belongsTo('App\\Model\\User'); //, 'user_id');
    }

    /**
     * Find meta facebook id, and return user
     * Makes testing easier when we don't have to chain eloquent methods
     * @param string $facebookId
     * @param App\Model\Meta $metaModel
     * @return User|null
     */
    public function findFacebookIdByUser(User $user)
    {
        $meta = $metaModel->where('name', 'facebook_id')
            ->where('value', $facebookId)
            ->first();

        if ($meta) {
            return $meta->user;
        } else {
            return null;
        }
    }
}
