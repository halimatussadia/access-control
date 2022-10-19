<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    public function login()
    {
       return view('admin.login');
    }


    public function doLogin(Request $request)
    {

        $userInfo=$request->except('_token');
        if(Auth::attempt($userInfo)){
            return redirect()->route('admin.dashboard')->with('message','Login successful.');
        }
        return redirect()->back()->with('error','Invalid user credentials');

    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('message','Logging out.');
    }



    public function downloadCertificate($test_id): JsonResponse
    {
        try {
            $passedUser = $this->certificateService->downloadCertificate($test_id);

            if(!$passedUser)
            {
                return $this->respondWithError('You did not passed.');
            }
            // check already file exist or not
            if($passedUser->certificate_path)
            {
                return $this->respondWithSuccess('certificate.',
                    ['file_path' => $passedUser->certificate_path]);
            }

            //generate certificate
            $certificate_filename='certificate_' . $passedUser->user_id . '_certificate.pdf';
            $passedUser->update([
                'certificate_id'=>date('Ymdhis').'_'.$passedUser->user_id,
                'certificate_path'=>$certificate_filename,
            ]);
            $path=route('certificate.verify',['certificate_id'=>$passedUser->certificate_id]);
            $qrcode = QrCode::generate($path);

            $pdf = Pdf::loadView('backend.certificate.certificate',compact('passedUser','qrcode'))
                ->setPaper('letter', 'landscape');
            $pdf->render();
            Storage::put(
                'certificates/'.$certificate_filename,
                $pdf->output()
            );

            return $this->respondWithSuccess('Certificate generate successfully.',
                ['file_path' => $passedUser->certificate_path]);

        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(),
                [
                    'errors'=>$th->getMessage()
                ]);
        }
    }
}
