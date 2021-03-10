import React, { useState, useEffect } from 'react';
import axios from 'axios';
import MaterialTable from 'material-table';
import TimeAgo from 'react-timeago';
// import { useFetch } from '../Helpers';

function RemoteOK(p) {
  const [data, setData] = useState([]);

  let url = new URL('https://remoteok.io/api');

  if (p.query) {
    url.searchParams.append('tags', p.query);
  }

  url = url.href;
  console.log(url);

  useEffect(() => {
    const fetchData = async () => {
      const result = await axios(url);
      setData(result.data);
    };

    fetchData();
  }, [url]);

  let json = data.slice(1).filter((d) => d.company !== '');

  const rows = json.map(({ date, position, company, url }) => ({
    date,
    position,
    company,
    url,
  }));

  const columns = [
    {
      title: 'Posted',
      field: 'date',
      render: (rowData) => <TimeAgo date={rowData.date} />,
      width: '20%',
      defaultSort: 'desc',
    },
    { title: 'Company', field: 'company', width: '30%' },
    {
      title: 'Position',
      field: 'position',
      render: (rowData) => <a href={rowData.url}>{rowData.position}</a>,
      width: '50%',
    },
  ];

  return (
    <div style={{ margin: '0 auto', maxWidth: '100%' }}>
      <MaterialTable
        onRowClick={() => null}
        columns={columns}
        data={rows}
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

export default RemoteOK;
