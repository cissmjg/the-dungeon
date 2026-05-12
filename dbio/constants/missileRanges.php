<?php
    const RANGE_POINT_BLANK = 1;
    const RANGE_SHORT = 2;
    const RANGE_MEDIUM = 3;
    const RANGE_LONG = 4;

    function getMissileRangeDescription($missile_range) {
        switch($missile_range) {
            case RANGE_POINT_BLANK:
                return 'Point Blank';
            case RANGE_SHORT:
                return 'Short';
            case RANGE_MEDIUM:
                return 'Medium';
            case RANGE_LONG:
                return 'Long';
            default:
                return 'UNKNOWN';
        }
    }

    enum MissileRange: string {
        case PointBlank = 'PointBlank';
        case Short = 'Short';
        case Medium = 'Medium';
        case MediumSwiftwing = 'Medium-Swiftwing';
        case Long = 'Long';
        case LongSwiftwing = 'Long-Swiftwing';
        case Other = 'Other';
    }
?>