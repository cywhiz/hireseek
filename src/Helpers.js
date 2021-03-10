import { useState, useEffect } from 'react';
import axios from 'axios';

function useFetch(url) {
  const [data, setData] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const result = await axios(url);
      setData(result.data);
    };

    fetchData();
  }, [url]);

  return data;
}

export { useFetch };
