require('./bootstrap');

import React from 'react';
import ReactDOM from 'react-dom';
import MainRouting from './Routing/MainRouting';

ReactDOM.render(
    <React.StrictMode>
        <MainRouting />
    </React.StrictMode>,
    document.getElementById('root')
);