<?php

namespace Aslam\Firebase;

use Exception;
use Kreait\Firebase\Factory as FirebaseFactory;

/**
 * @author Vũ Thế Quân <quanvt.dev@gmail.com>
 * @package QuanVT/laravel-firebase-sync
 * Class SyncsWithFirebase
 * Synchronize Eloquent models with a Firebase realtime database.
 */
trait SyncWithFirebase
{
    /**
     * Boot the trait and add the model events to synchronize with firebase
     */
    public static function bootSyncWithFirebase()
    {
        static::created(function ($model) {
            $model->saveToFirebase('store');
        });
        static::updated(function ($model) {
            $model->saveToFirebase('update');
        });
        static::deleted(function ($model) {
            $model->saveToFirebase('delete');
        });
        if (function_exists('restored')) {
            static::restored(function ($model) {
                $model->saveToFirebase('restore');
            });
        }
    }

    /**
     * @return array
     */
    protected function getFirebaseSyncData()
    {
        if ($fresh = $this->fresh()) {
            return $fresh->toArray();
        }
        return [];
    }

    /**
     * @param $actionType
     */
    protected function saveToFirebase($actionType)
    {
        if ($actionType === 'store') {
            $this->firebaseFactory()->set($this->getFirebaseSyncData());
        } elseif ($actionType === 'update') {
            $this->firebaseFactory()->set($this->getFirebaseSyncData());
        } elseif ($actionType === 'delete') {
            $this->firebaseFactory()->remove();
        } elseif ($actionType === 'restore') {
            $this->firebaseFactory()->set($this->getFirebaseSyncData());
        }
    }

    /**
     * @return FirebaseFactory
     */
    protected function firebaseFactory()
    {
        $path = $this->getTable() . '/' . $this->getKey();
        $firebaseConfig = config_path('firebase.json');
        if (!file_exists($firebaseConfig)) {
            if (!config('services.firebase')) {
                throw new Exception('please add config/firebase.json file or service.firebase array');
            }
            return (new FirebaseFactory)
                ->withServiceAccount(config('services.firebase'))
                ->withDatabaseUri(config('services.firebase.database_url'))
                ->createDatabase()
                ->getReference($path);
        }
        return (new FirebaseFactory)
            ->withServiceAccount($firebaseConfig)
            ->createDatabase()
            ->getReference($path);
    }
}
