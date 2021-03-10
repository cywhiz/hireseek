import React from 'react';
import { BrowserRouter, Route } from 'react-router-dom';
import { Helmet } from 'react-helmet';
import Results from './components/Results';
import RemoteOK from './components/RemoteOK';
// import StackOverflow from './components/StackOverflow';
import logo from './logo_small.png';
import './App.css';

function App() {
  return (
    <BrowserRouter>
      <div className="App">
        <Route path="/RemoteOK" component={RemoteOK} />
        <Helmet>
          <meta charset="utf-8" />
          <title>Hireseek</title>
        </Helmet>

        <img src={logo} alt="logo" />
        <Results />

        {/* <RemoteOK /> */}
        {/* <StackOverflow /> */}
      </div>
    </BrowserRouter>
  );
}

export default App;
