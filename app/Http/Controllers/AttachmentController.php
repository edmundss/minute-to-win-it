<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use File;
use App\Attachment;
use App\Comment;

class AttachmentController extends Controller
{
    /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate(
            $request, 
            array(
				'attachment' => array('required','max:2000'),
				'parent_class' => array('required'),
				'parent_id' => array('required'),
            )
        );

		$user = Auth::user();
		$upload = $request->file('attachment');
		$parent_class = $request->input('parent_class');
		$parent_id = $request->input('parent_id');

		
		$public_path = public_path();
		if (strlen($public_path) > 0 && substr($public_path, -1) != '/')
		{
			$public_path = $public_path . '/';
		}

		$destinationPath =  $public_path . 'uploads/attachments/' . $parent_class . '/' . $parent_id;

		$filename = $upload->getClientOriginalName();
		$filename = preg_replace('/[^a-zA-Z0-9-_\.]/','_', $filename);
		$upload->move($destinationPath, $filename);
		
		$attachment = new Attachment;
		$attachment->filename = $filename;
		$attachment->parent_class = $parent_class;
		$attachment->parent_id = $parent_id;
		$attachment->user_id = $user->id;
		$attachment->save();

		return redirect()->back()->withMessage('Attachement saved');
	}

	public static function saveAttachment($file, $parent_class, $parent_id, $user)
	{
		$public_path = public_path();
		if (strlen($public_path) > 0 && substr($public_path, -1) != '/')
		{
			$public_path = $public_path . '/';
		}

		$destinationPath =  $public_path . 'uploads/attachments/' . $parent_class . '/' . $parent_id;

		$filename = $file->getClientOriginalName();
		$filename = preg_replace('/[^a-zA-Z0-9-_\.]/','_', $filename);
		$file->move($destinationPath, $filename);
		
		$attachment = new Attachment;
		$attachment->filename = $filename;
		$attachment->parent_class = $parent_class;
		$attachment->parent_id = $parent_id;
		$attachment->user_id = $user->id;
		$attachment->save();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$attachment = Attachment::findOrFail($id);

		$public_path = public_path();
		if (strlen($public_path) > 0 && substr($public_path, -1) != '/')
		{
			$public_path = $public_path . '/';
		}

		$filepath = $public_path . 'uploads/attachments/' . $attachment->parent_class . '/' . $attachment->parent_id . '/';
		$filename = $attachment->filename;
	    

	    return response()->download($filepath . $filename);
	}

	public function delete($id)
	{
		$attachment = Attachment::findOrFail($id);


		$public_path = public_path();
		if (strlen($public_path) > 0 && substr($public_path, -1) != '/')
		{
			$public_path = $public_path . '/';
		}

		$filePath =  $public_path . 'uploads/attachments/' . $attachment->parent_class . '/' . $attachment->parent_id . '/' . $attachment->filename;

		File::delete($filePath);

		Comment::save_comment($attachment->parent_class, $attachment->parent_id, 'Es dzēsu datni ' . $attachment->filename);

		$attachment->delete();

		return redirect()->back()->withMessage('Pievienotā datne tika sekmīgi dzēsta!');
	}
}
