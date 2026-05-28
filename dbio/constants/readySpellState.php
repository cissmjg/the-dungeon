<?php

    enum ReadySpellState: string {
        case Ready = 'Ready';
        case AlreadyCast = 'Already Cast';
        case Casting = 'Casting';
        case Running = 'Running';
    }
?>