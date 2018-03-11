<?php
/**
 * Created by PhpStorm.
 * User: wasif baig
 * Date: 06/03/2018
 * Time: 15:36
 */

namespace App\Http\Controllers;


class BurroughsController
{

    /*
     * payment dates for the next twelve months
     *@param string $filename
     *
     * @return file
     */
    public function paymentDownloadCsvFile($filename)
    {

        $filename .= '.csv';
        $data = [array('Month name','Salary payment date','Bonus payment date')];

        for($i=1; $i<=12; $i++)
        {

            $date = date('Y-m', strtotime("+$i month"));
            $salaryPaymentDate = $this->getLastWorkingDate($date);
            $bonusPaymentDate = $this->getWednesdayAfter15($date);

            $monthName = date('F Y', strtotime($date));
            array_push($data,array($monthName,$salaryPaymentDate,$bonusPaymentDate));

        }


        /*
         * Download CSV file
         */

        try {

            $this->createCsvDownloadFile($data,$filename);
             return true;
        } catch (Exception $e) {
            return false;
        }


    }


    /*
     * Get last working date of given month
     *
     * @param date $month
     * @return date
     */

    public function getLastWorkingDate($month)
    {

        $date = date('Y-m-t', strtotime($month));
        $dayName = date('l', strtotime($date));

        if($dayName == "Saturday" || $dayName == "Sunday")
            $lastWorkingDate = date ('Y-m-d', strtotime ($date . 'last friday'));
        else
            $lastWorkingDate = $date;


        return $lastWorkingDate;

    }


    /*
     * Get date of 15 or wednwsday after 15
     *
     * @param date month
     * @return date
     */

    public function getWednesdayAfter15($month)
    {

        $date = date('Y-m-15', strtotime($month));
        $dayName = date('l', strtotime($date));

        if($dayName == "Saturday" || $dayName == "Sunday")
            $wednesdayAfter15Date = date ('Y-m-d', strtotime ($date . 'next wednesday'));
        else
            $wednesdayAfter15Date = $date;


        return $wednesdayAfter15Date;

    }

    /*
     * Create CSV file from array
     *
     * @param array $data
     * @param string $filename
     * @param string $delimiter
     *
     * @return file
     */

    function createCsvDownloadFile($data, $filename = "export.csv", $delimiter=",") {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen($filename, 'w');
        // loop over the input array
        foreach ($data as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }


}