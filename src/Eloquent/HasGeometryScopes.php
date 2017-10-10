<?php 
namespace Phaza\LaravelPostgis\Eloquent;

trait HasGeometryScopes
{
    public function scopeWithin($query, $geometryColumn, $geometry)
    {   
        // SQL syntax:  "ST_Within(geometry A, geometry B)"
        // Example:     "ST_Within(point::geometry, ST_GeomFromText('POLYGON ((30 10, 20 40, 10 20, 30 10))', 4326 ))"
        
        // Preparing parts
        $geometryA = $geometryColumn . "::geometry"; 
        $geometryB = "ST_GeomFromText('" . $geometry->toWKT() . "', " . $this->postgisTypes[$geometryColumn]['srid'] . ")";
        
        // Assumption is made that geometry A and geometry B has the same SRID
        return $query->whereRaw("ST_Within(" . $geometryA . "," . $geometryB . ")");        
    }
}