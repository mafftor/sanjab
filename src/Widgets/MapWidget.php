<?php

namespace Sanjab\Widgets;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use stdClass;
use Illuminate\Database\Eloquent\Model;

/**
 * Map marker input.
 *
 * @property string $latitudeName(string $val)  latitude field name
 * @property string $longitudeName(string $val) longitude field name
 */
class MapWidget extends Widget
{
    public function init()
    {
        $this->onIndex(false)
            ->sortable(false)
            ->searchable(false)
            ->tag("map-widget")
            ->viewTag("map-view");
    }

    protected function store(Request $request, Model $item)
    {
        $item->{ $this->property("latitudeName") } = $request->input($this->property("name").".lat");
        $item->{ $this->property("longitudeName") } = $request->input($this->property("name").".lng");
    }

    protected function modifyResponse(stdClass $response, Model $item)
    {
        $response->{ $this->property("name") } = [
            'lat' => $item->{ $this->property("latitudeName") },
            'lng' => $item->{ $this->property("longitudeName") },
        ];
    }

    public function validationRules(Request $request, string $type, Model $item = null): array
    {
        return [
            $this->name => $this->property('rules.'.$type, []),
            $this->property("name").".lat"  => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            $this->property("name").".lng"  => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];
    }

    /**
     * Set name and lat and lng name.
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->setProperty('name', $name);
        $this->latitudeName($name.'_lat');
        $this->latitudeName($name.'_lng');
    }
}
