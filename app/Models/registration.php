<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registration extends Model
{
    //
    protected $connection = 'visitor';
    protected $table = 'tb_registrations';
    // public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    protected $fillable = [
        'Exhibition',
        'NameTitle',
        'Name',
        'Company',
        'JobTitle',
        'Address',
        'State',
        'Country',
        'Telephone',
        'MobilePhone',
        'Fax',
        'Email',
        'BusinessType',
        'JobFunction',
        'JobFunctionOther',
        'VisitPurpose',
        'VisitPurposeOther',
        'PurchasingRole',
        'PurchasingRoleOther',
        'EventFind',
        'EventFindOther',
        'IsReceivedInvitationNext',
        'IsReceivedInvitationNextAddressSame',
        'ReceivedInvitationNextAddress',
        'Barcode',
        'IsPrinted',
        'RegisterDate',
        'LastCheckinTime',
        'LastCheckinLocation',
        'SubExhibition',
        'SubExhibitionName',
        'FirstName',
        'LastName',
        'CompanyType',
        'JobLevel',
        'Departement',
        'Website',
        'SubExhibitionName',
        'SubExhibitionName',
        'HowKnow',
        'LineOfBusiness',
        'City',
        'IsReciveNewsletter',
        'IsAgreePolicy',
    ];
}
