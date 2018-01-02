<?php

namespace App;


trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {

        if (auth()->guest())
        {
            return;
        }

        // A more generic, polymorphic version of the below thread-specific function
        foreach (static::getActivitiesToRecord() as $event)
        {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

//        static::created(function ($thread){
//            $thread->recordActivity('created');
//        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * @param $event
     */
    public function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
        
//        Activity::create([
//            'user_id' => auth()->id(),
//            'type' => $this->getActivityType($event),
//            'subject_id' => $this->id,
//            'subject_type' => get_class($this)
//        ]);
    }



    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event): string
    {
        $type = strtoLower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}