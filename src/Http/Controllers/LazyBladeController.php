<?php

namespace captenmasin\LazyBlade\Http\Controllers;

use Illuminate\Http\Request;

class LazyBladeController
{
    public function __invoke(Request $request)
    {
        $data = json_decode($request->data);
        $bladeData = [];
        foreach ($data as $key => $value) {
            if (isset($value->id) || isset($value->uuid)) {
                $modelNameKey = ucfirst($key);
                if ($modelNameKey == 'Notification' || $modelNameKey == 'Notifications') {
                    $modelName = "\Illuminate\Notifications\DatabaseNotification";
                    $model = $modelName::find($value->id);
                    $bladeData[$key] = $model;
                } else {
                    $modelName = $value->namespace;
                    $bladeData[$key] = $modelName::find($value->id);
                }
            } else {
                $bladeData[$key] = $value;
            }
        }

        $bladeFile = $request->blade;
        if (Str::contains($bladeFile, ',')) {
            $possiblePartials = explode(',', $bladeFile);
            foreach ($possiblePartials as $possiblePartial) {
                if (\View::exists($possiblePartial)) {
                    $bladeFile = $possiblePartial;
                }
            }
        }

        return view($bladeFile, $bladeData)->render();
    }
}
