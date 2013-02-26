//
//  mlViewCustomNotice.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-18.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewCustomLayer.h"
#import <QuartzCore/QuartzCore.h>

@interface mlViewCustomNotice : mlViewCustomLayer

@property (assign , nonatomic) UIView *bg;
@property (assign , nonatomic) UITapGestureRecognizer *tapGesture;

- (void)showInView:(UIView *)view forSecond:(int)sec;

@end
