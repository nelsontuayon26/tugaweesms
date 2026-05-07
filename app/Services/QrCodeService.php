<?php

namespace App\Services;

use App\Models\SchoolYear;
use App\Models\SchoolYearQrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Carbon\Carbon;

class QrCodeService
{
    /**
     * Generate a QR code for a school year
     */
    public function generateForSchoolYear(SchoolYear $schoolYear): SchoolYearQrCode
    {
        // Deactivate old QR codes
        SchoolYearQrCode::where('school_year_id', $schoolYear->id)
            ->update(['is_active' => false]);

        // Generate unique token
        $token = Str::random(32);

        // Build enrollment URL using prefixed route
        $enrollmentUrl = route('admin.enrollment.form.qr', ['token' => $token]);

        // Generate QR code image
        $qrCode = QrCode::create($enrollmentUrl)
            ->setSize(400)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Save image to storage
        $filename = "qr-codes/school-year-{$schoolYear->id}-{$token}.png";
        Storage::disk('public')->put($filename, $result->getString());

        // Handle expires_at: use end_date if valid, otherwise 1 year from now
        $expiresAt = $this->getValidExpiresAt($schoolYear);

        // Create DB record
        $qrCodeRecord = SchoolYearQrCode::create([
            'school_year_id' => $schoolYear->id,
            'qr_code_token' => $token,
            'qr_code_image_path' => $filename,
            'is_active' => true,
            'expires_at' => $expiresAt,
        ]);

        return $qrCodeRecord;
    }

    /**
     * Validate a QR code token
     */
    public function validateToken(string $token): ?SchoolYearQrCode
    {
        $qrCode = SchoolYearQrCode::where('qr_code_token', $token)
            ->with('schoolYear')
            ->first();

        if (!$qrCode || !$qrCode->isValid()) {
            return null;
        }

        return $qrCode;
    }

    /**
     * Ensure expires_at is valid for MySQL
     */
    protected function getValidExpiresAt(SchoolYear $schoolYear): string
    {
        // Use end_date if it exists and is before MySQL max datetime
        $maxDatetime = Carbon::createFromFormat('Y-m-d H:i:s', '2037-12-31 23:59:59');

        if ($schoolYear->end_date) {
            $endDate = Carbon::parse($schoolYear->end_date);
            return $endDate->lessThanOrEqualTo($maxDatetime) 
                ? $endDate->toDateTimeString() 
                : $maxDatetime->toDateTimeString();
        }

        // Fallback: 1 year from now, but not beyond 2037-12-31
        $fallback = now()->addYear();
        return $fallback->lessThanOrEqualTo($maxDatetime) 
            ? $fallback->toDateTimeString() 
            : $maxDatetime->toDateTimeString();
    }
}