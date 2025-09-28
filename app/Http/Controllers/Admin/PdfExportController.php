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
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $tableData, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Remove unwanted elements
        $xpath = new \DOMXPath($dom);
        $elementsToRemove = $xpath->query('//button | //a[contains(@class, "btn")] | //*[contains(@class, "btn")] | //*[contains(@class, "badge")] | //script | //style | //*[contains(@class, "collapse")] | //*[@style="display: none"]');
        foreach ($elementsToRemove as $element) {
            if ($element->parentNode) {
                $element->parentNode->removeChild($element);
            }
        }
        
        // Remove empty rows
        $emptyRows = $xpath->query('//tr[not(td[normalize-space()]) and not(th[normalize-space()])]');
        foreach ($emptyRows as $row) {
            if ($row->parentNode) {
                $row->parentNode->removeChild($row);
            }
        }
        
        $headers = [];
        $rows = [];
        
        // Extract headers
        $headerNodes = $dom->getElementsByTagName('th');
        foreach ($headerNodes as $header) {
            $headerText = trim(preg_replace('/\s+/', ' ', $header->textContent));
            if (!empty($headerText)) {
                $headers[] = $headerText;
            }
        }
        
        // Extract rows
        $rowNodes = $dom->getElementsByTagName('tr');
        foreach ($rowNodes as $row) {
            $cells = $row->getElementsByTagName('td');
            if ($cells->length > 0) {
                $rowData = [];
                foreach ($cells as $cell) {
                    $cellText = trim(preg_replace('/\s+/', ' ', $cell->textContent));
                    // Limit long text and remove empty cells
                    if (strlen($cellText) > 50) {
                        $cellText = substr($cellText, 0, 47) . '...';
                    }
                    $rowData[] = $cellText;
                }
                // Only add rows that have meaningful content
                $hasContent = false;
                foreach ($rowData as $cell) {
                    if (!empty($cell) && $cell !== '...' && strlen($cell) > 1) {
                        $hasContent = true;
                        break;
                    }
                }
                if ($hasContent) {
                    $rows[] = $rowData;
                }
            }
        }
        
        // Get school data based on user type
        $school = null;
        $user = auth()->user();
        
        if ($user->user_type === 'school') {
            $school = \App\Models\SchoolAccount::where('user_id', $user->id)->first();
        } elseif ($user->user_type === 'teacher') {
            $school = $user->teacher?->schoolAccount;
        }
        
        $html = view('admin.pdf.table', compact('headers', 'rows', 'title', 'school'))->render();
        
        // Clean the title for header
        $cleanTitle = preg_replace('/[^\w\s\x{0600}-\x{06FF}]/u', '', $title);
        $cleanTitle = str_replace(["\n", "\r", "\t"], '', $cleanTitle);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="' . $cleanTitle . '.html"');
    }
}