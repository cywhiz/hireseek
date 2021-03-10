import React, { useState, useEffect } from 'react';
import axios from 'axios';
import MaterialTable from 'material-table';
import TimeAgo from 'react-timeago';
// import { useFetch } from '../Helpers';

function StackOverflow(p) {
  const [data, setData] = useState([]);

  const api_key = 'wogzssyjex8rz6kplrbexu0fepg5kq3czens4kwn';
  let url = new URL('https://api.rss2json.com/v1/api.json');
  let rss = new URL('https://stackoverflow.com/jobs/feed');

  if (p.query) {
    rss.searchParams.append('q', p.query);
  }

  if (p.location) {
    rss.searchParams.append('l', p.location);
  }

  url.searchParams.append('api_key', api_key);
  url.searchParams.append('count', 1000);
  url.searchParams.append('rss_url', encodeURI(rss));
  url = url.href;

  console.log(url);

  useEffect(() => {
    const fetchData = async () => {
      const result = await axios(url);
      let items = result.data.items;

      for (let i in items) {
        let t = items[i].title;
        items[i].title = t.substring(0, t.indexOf(' at '));
      }

      setData(items);
    };

    fetchData();
  }, [url]);

  const rows = data.map(({ author, link, pubDate, title }) => ({
    author,
    link,
    pubDate,
    title,
  }));

  const columns = [
    {
      title: 'Posted',
      field: 'pubDate',
      render: (rowData) => <TimeAgo date={rowData.pubDate} />,
      width: '20%',
      defaultSort: 'desc',
    },
    { title: 'Company', field: 'author', width: '30%' },
    {
      title: 'Position',
      field: 'title',
      render: (rowData) => <a href={rowData.link}>{rowData.title}</a>,
      width: '50%',
    },
  ];

  return (
    <div style={{ margin: '0 auto', maxWidth: '100%' }}>
      <MaterialTable
        onRowClick={() => null}
        columns={columns}
        data={rows}
        title="StackOverflow"
        options={{
          showTitle: false,
          pageSize: 50,
          thirdSortClick: false,
          pageSizeOptions: [100],
          emptyRowsWhenPaging: false,
          headerStyle: {
            backgroundColor: '#0099CC',
            color: '#FFF',
          },
        }}
      />
    </div>
  );
}

export default StackOverflow;
