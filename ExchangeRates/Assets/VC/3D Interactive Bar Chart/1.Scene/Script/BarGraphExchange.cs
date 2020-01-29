using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System;
using BarGraph.VittorCloud;
using System.IO;
using System.Globalization;
using UnityEngine.Networking;


public class BarGraphExchange : MonoBehaviour
{
    public List<BarGraphDataSet> exchDataSet; // public data set for inserting data into the bar graph
    BarGraphGenerator barGraphGenerator;

    void Start()
    {
        //if the list is empty, exit
        if (exchDataSet.Count == 0)
        {
            Debug.LogError("exchDataSet is Empty!");
            return;
        }

        barGraphGenerator = GetComponent<BarGraphGenerator>();

        var countries = new Dictionary<string, int>();
        countries.Add("Mexico", 0);
        countries.Add("India", 1);
        countries.Add("Hong Kong", 2);
        countries.Add("Denmark", 3);
        countries.Add("China", 4);
        countries.Add("Brazil", 5);
        countries.Add("Malaysia", 6);
        countries.Add("Australia", 7);
        countries.Add("Canada", 8);
        countries.Add("Euro", 9);
        countries.Add("Japan", 10);
        countries.Add("New Zealand", 11);
        countries.Add("Norway", 12);
        countries.Add("Singapore", 13);
        countries.Add("South Africa", 14);
        countries.Add("South Korea", 15);
        countries.Add("Sweden", 16);
        countries.Add("Switzerland", 17);
        countries.Add("Taiwan", 18);
        countries.Add("Thailand", 19);
        countries.Add("Venezuela", 20);

        var countriesStatus = new Dictionary<string, int>();
        countriesStatus.Add("Australia", 0);
        countriesStatus.Add("Brazil", 0);
        countriesStatus.Add("Canada", 0);
        countriesStatus.Add("China", 0);
        countriesStatus.Add("Denmark", 0);
        countriesStatus.Add("Euro", 0);
        countriesStatus.Add("Hong Kong", 0);
        countriesStatus.Add("India", 0);
        countriesStatus.Add("Japan", 0);
        countriesStatus.Add("Malaysia", 0);
        countriesStatus.Add("Mexico", 0);
        countriesStatus.Add("New Zealand", 0);
        countriesStatus.Add("Norway", 0);
        countriesStatus.Add("Singapore", 0);
        countriesStatus.Add("South Africa", 0);
        countriesStatus.Add("South Korea", 0);
        countriesStatus.Add("Sweden", 0);
        countriesStatus.Add("Switzerland", 0);
        countriesStatus.Add("Taiwan", 0);
        countriesStatus.Add("Thailand", 0);
        countriesStatus.Add("Venezuela", 0);

        String fileData;

#if UNITY_EDITOR
        fileData = File.ReadAllText(Application.dataPath + "/Resources/yearly_csv.csv");
        
#elif UNITY_STANDALONE
        fileData = File.ReadAllText(Application.dataPath + "\\Resources\\yearly_csv.csv");

#elif UNITY_WEBGL
        UnityWebRequest www = UnityWebRequest.Get(Application.dataPath + "/Resources/yearly_csv.csv");
        fileData = www.downloadHandler.text;
#endif

        fileData = fileData.Replace("Date,Country,Value", "").Trim();
        String[] lines = fileData.Split("\n"[0]);

        CultureInfo ci = (CultureInfo)CultureInfo.CurrentCulture.Clone();
        ci.NumberFormat.CurrencyDecimalSeparator = ".";

        foreach (String line in lines)
        {
            String[] lineData = (line.Trim()).Split(","[0]);

            int countryId = countries[lineData[1]];
            if(countryId == 0 || countryId < 10)
            {
                if(countriesStatus[lineData[1]] < 10)
                {
                    exchDataSet[countryId].GroupName = lineData[1];
                    exchDataSet[countryId].ListOfBars[countriesStatus[lineData[1]]].XValue = lineData[0];
                    exchDataSet[countryId].ListOfBars[countriesStatus[lineData[1]]].YValue = float.Parse(lineData[2], NumberStyles.Any, ci);
                    countriesStatus[lineData[1]]++;
                }

            }

        }

        barGraphGenerator.GeneratBarGraph(exchDataSet);

    }


    static IEnumerator GetZIP()
    {        
        UnityWebRequest www = UnityWebRequest.Get("https://datahub.io/core/exchange-rates/r/exchange-rates_zip.zip");
        yield return www.SendWebRequest();
        
               if(www.isNetworkError || www.isHttpError) {
                   Debug.Log(www.error);
               }
               else {
                   // Show results as text
                   //Debug.Log(www.downloadHandler.text);

                   //  retrieve results as binary data
                   byte[] results = www.downloadHandler.data;
        //           print(Application.dataPath);
       			    File.WriteAllBytes(Application.dataPath + "\\Resources\\test.zip", results);
               }
    }



    //call when the graph starting animation completed,  for updating the data on run time
    public void StartUpdatingGraph()
    {
        StartCoroutine(CreateDataSet());
    }



    IEnumerator CreateDataSet()
    {
        //  yield return new WaitForSeconds(3.0f);
        while (true)
        {

            GenerateRandomData();

            yield return new WaitForSeconds(2.0f);

        }

    }

    //Generates the random data for the created bars
    void GenerateRandomData()
    {
        int dataSetIndex = UnityEngine.Random.Range(0, exchDataSet.Count);
        int xyValueIndex = UnityEngine.Random.Range(0, exchDataSet[dataSetIndex].ListOfBars.Count);
        exchDataSet[dataSetIndex].ListOfBars[xyValueIndex].YValue = UnityEngine.Random.Range(barGraphGenerator.yMinValue, barGraphGenerator.yMaxValue);
        barGraphGenerator.AddNewDataSet(dataSetIndex, xyValueIndex, exchDataSet[dataSetIndex].ListOfBars[xyValueIndex].YValue);
    }
}