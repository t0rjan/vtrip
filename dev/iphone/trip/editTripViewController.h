//
//  editTripViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-3-12.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "newTripFormViewController.h"
#import "toolTime.h"

@interface editTripViewController : newTripFormViewController <UIActionSheetDelegate>

@property (assign , nonatomic) NSMutableDictionary *tripInfo;

@end
