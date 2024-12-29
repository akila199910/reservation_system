<?php

namespace App\Repositories;

use App\Models\IntakeForm;

class IntakeFormRepository
{
    public function create_intakeForm($request)
    {
        // Create the intake form
        $intake = new IntakeForm();
        $intake->business_id = $request->business_id; // Set the business ID
        $intake->f_name = $request->f_name;
        $intake->l_name = $request->l_name;
        $intake->email = $request->email;
        $intake->contact = $request->contact;
        $intake->address = $request->address;
        $intake->dob = $request->dob;
        $intake->gender = $request->gender;
        $intake->reason = $request->reason;
        $intake->description = $request->description;
        $intake->appointment_time = $request->appointment_time;
        $intake->appointment_date = $request->appointment_date;
        $intake->communication_mode = $request->communication_mode;
        $intake->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $intake->id);
        $intake->ref_no = $ref_no;
        $intake->update();
        // Return response with the created ID
        return ['id' => $intake->id];
    }


    public function update_intakeForm($request)
    {
        $intake = IntakeForm::find($request->id);

        if (!$intake) {
            return [
                'status' => false,
                'message' => 'Intake Form Not Found'
            ];
        }

        $intake = IntakeForm::find($request->id);
        $intake->f_name = $request->f_name;
        $intake->l_name = $request->l_name;
        $intake->email = $request->email;
        $intake->contact = $request->contact;
        $intake->address = $request->address;
        $intake->appointment_date = $request->appointment_date;
        $intake->appointment_time = $request->appointment_time;
        $intake->communication_mode = $request->communication_mode;
        $intake->reason = $request->reason;
        $intake->description = $request->description;
        $intake->update();

        return [
            'status' => true,
            'message' => 'Selected Intake Form Updated Successfully!'
        ];
    }
    public function delete_intakeForm($request)
    {
        $business = IntakeForm::find($request->id);

        if (!$business) {
            return [
                'status' => false,
                'message' => 'Intake Form Not Found'
            ];
        }

        $business->delete();

        return [
            'status' => true,
            'message' => 'Selected Intake Form Deleted Successfully'
        ];
    }
}
