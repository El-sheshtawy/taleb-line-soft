<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PdfExportController extends Controller
{
    public function exportTable(Request $request)
    {
        $tableData = $request->input('tableData');
        $title = $request->input('title', 'تقرير');
        
        // Parse table data to extract text content
        $dom = new \DOMDocument();
        @$dom->loadHTML('<meta charset="UTF-8">' . $tableData);
        
        // Remove buttons and action elements
        $xpath = new \DOMXPath($dom);
        $buttons = $xpath->query('//button | //a[@class] | //*[contains(@class, "btn")] | //*[contains(@class, "badge")]');
        foreach ($buttons as $button) {
            $button->parentNode->removeChild($button);
        }
        
        $headers = [];
        $rows = [];
        
        // Extract headers
        $headerNodes = $dom->getElementsByTagName('th');
        foreach ($headerNodes as $header) {
            $headers[] = trim($header->textContent);
        }
        
        // Extract rows
        $rowNodes = $dom->getElementsByTagName('tr');
        foreach ($rowNodes as $row) {
            $cells = $row->getElementsByTagName('td');
            if ($cells->length > 0) {
                $rowData = [];
                foreach ($cells as $cell) {
                    $rowData[] = trim($cell->textContent);
                }
                $rows[] = $rowData;
            }
        }
        
        // Get school data
        $school = auth()->user()->schoolAccount ?? null;
        
        $html = view('admin.pdf.table', compact('headers', 'rows', 'title', 'school'))->render();
        
        // Clean the title for header
        $cleanTitle = preg_replace('/[^\w\s\x{0600}-\x{06FF}]/u', '', $title);
        $cleanTitle = str_replace(["\n", "\r", "\t"], '', $cleanTitle);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="' . $cleanTitle . '.html"');
    }
}