import React, { useState } from 'react';

import { makeStyles } from '@material-ui/core/styles';
import TextField from '@material-ui/core/TextField';
import Select from '@material-ui/core/Select';
import FormControl from '@material-ui/core/FormControl';
import MenuItem from '@material-ui/core/MenuItem';
import Button from '@material-ui/core/Button';
import InputLabel from '@material-ui/core/InputLabel';

import Indeed from './Indeed';
import RemoteOK from './RemoteOK';
import StackOverflow from './StackOverflow';

// import { Redirect } from 'react-router-dom';

const useStyles = makeStyles((theme) => ({
  root: {
    '& > *': {
      margin: theme.spacing(1),
      minWidth: 120,
    },
    formControl: {
      margin: theme.spacing(1),
      minWidth: 120,
    },
  },
}));

function Results() {
  const classes = useStyles();

  const [query, setQuery] = useState('');
  const [location, setLocation] = useState('');
  const [site, setSite] = useState('');
  const [submitted, setSubmitted] = useState(false);

  const [results, setResults] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    // this.props.history.push('/RemoteOK');
    console.log('query:', query, 'site: ', site);

    setSubmitted(true);

    // const history = useHistory();
    // history.push('/RemoteOK');
    switch (site) {
      case 'Indeed':
        setResults(<Indeed query={query} location={location} />);
        break;
      case 'RemoteOK':
        setResults(<RemoteOK query={query} location={location} />);
        break;
      case 'StackOverflow':
        setResults(<StackOverflow query={query} location={location} />);
        break;
      default:
        setResults('');
    }
  };

  return (
    <div>
      <form
        className={classes.root}
        noValidate
        autoComplete="off"
        onSubmit={handleSubmit}
      >
        <TextField
          id="query"
          value={query}
          label="Job Title"
          onChange={(e) => setQuery(e.target.value)}
        />
        <TextField
          id="location"
          value={location}
          label="Location"
          onChange={(e) => setLocation(e.target.value)}
        />
        <FormControl className={classes.formControl}>
          <InputLabel id="site">Site</InputLabel>
          <Select
            labelId="site"
            id="site"
            value={site}
            onChange={(e) => setSite(e.target.value)}
          >
            <MenuItem value="RemoteOK">RemoteOK</MenuItem>
            <MenuItem value="StackOverflow">StackOverflow</MenuItem>
            {/* <MenuItem value="Indeed">Indeed</MenuItem> */}
          </Select>
        </FormControl>
        <Button variant="contained" color="primary" type="submit">
          Submit
        </Button>
      </form>

      {submitted && results}
    </div>
  );
}

export default Results;
