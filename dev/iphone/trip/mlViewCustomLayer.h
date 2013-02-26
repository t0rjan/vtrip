//
//  mlViewCustomLayer.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-16.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface mlViewCustomLayer : UIView

- (void)showInView:(UIView *)view;
- (void)show;
- (void)hide;
- (void)hideAll;
- (void)hideFromView;


- (void)_show;
- (void)_hide;
- (void)_show_complete;
- (void)_show_begin;
- (void)_hide_complete;
- (void)_hide_begin;


@property (assign , nonatomic) NSString *layerId;
@property (assign , nonatomic) BOOL isShowing;
@property (assign , nonatomic) float duration;

@end
