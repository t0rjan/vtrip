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


@interface newTripViewController : modelBaseViewController<UITextFieldDelegate,UIPickerViewDelegate , UIPickerViewDataSource>

- (IBAction)resignInput;
@end
