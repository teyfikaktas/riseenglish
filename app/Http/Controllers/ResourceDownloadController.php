<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Downloader;
use App\Models\ResourceDownload;
use App\Models\Resource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ResourceDownloadController extends Controller
{
    /**
     * Download resource and store downloader information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'newsletter' => 'sometimes',
            'resource_id' => 'required|exists:resources,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find or create downloader by email
            $downloader = Downloader::firstOrNew([
                'email' => $request->email
            ]);

            // If downloader already exists, update only if some fields are empty
            if ($downloader->exists) {
                // Only update name if it was empty
                if (empty($downloader->fullname)) {
                    $downloader->fullname = $request->fullname;
                }
                
                // Only update phone if it was empty
                if (empty($downloader->phone)) {
                    $downloader->phone = $request->phone;
                }
                
                // Update subscription preference if requested
                if ($request->has('newsletter')) {
                    $downloader->is_subscribed = true;
                }
            } else {
                // New downloader, set all fields
                $downloader->fullname = $request->fullname;
                $downloader->phone = $request->phone;
                $downloader->is_subscribed = $request->has('newsletter');
              //  $downloader->ip_address = $request->ip();
            }

            $downloader->save();

            // Get the resource
            $resource = Resource::findOrFail($request->resource_id);

            // Her indirmede yeni bir kayıt oluştur
            ResourceDownload::create([
                'downloader_id' => $downloader->id,
                'resource_id' => $resource->id,
              //  'ip_address' => $request->ip()
            ]);

            // Indirme sayacını artır
            $resource->increment('download_count');

            // Return download URL
            return response()->json([
                'success' => true,
                'download_url' => asset('storage/' . $resource->file_path)
            ]);

        } catch (\Exception $e) {
            Log::error('Resource download error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyin.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}