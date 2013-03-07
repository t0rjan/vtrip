//
//  interactiveViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "photoCommentViewController.h"
#import "photoLikeViewController.h"
@interface interactiveViewController : UIViewController

@property (assign , nonatomic) NSString *which;

- (void)changeWhich:(int)which;
@end
