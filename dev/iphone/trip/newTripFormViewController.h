//
//  newTripViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-8.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "modelBaseViewController.h"
#import "mlViewCustomActionSheet.h"
#import "mlViewCustomNotice.h"
#import "toolIosSize.h"
#import "toolString.h"
#import "modelMyTrip.h"
#import "modelUserBase.h"



@interface newTripFormViewController : modelBaseViewController<UITextFieldDelegate,UIPickerViewDelegate , UIPickerViewDataSource>

@property (retain , nonatomic) UITextField *tripWhere;
@property (retain , nonatomic) UITextField *tripTitle;
@property (retain , nonatomic) UILabel *tripDate;
@property (retain , nonatomic) UILabel *tripDays;
@property (retain , nonatomic) UILabel *noticeLabel;
@property (retain , nonatomic) UIDatePicker *datePicker;
@property (retain , nonatomic) UIPickerView *daysPicker;
@property (assign , nonatomic) modelMyTrip *modelMyTrip;


- (IBAction)resignInput;
- (BOOL)isAllInputAvailable;
@end
